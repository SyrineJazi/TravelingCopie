<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Reservation;
use App\Form\EventType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ActiviteRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Knp\Component\Pager\PaginatorInterface;

class EventController extends AbstractController
{
    #[Route('/event', name: 'app_event')]
    public function index(): Response
    {
        return $this->render('Event/event.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }

    #[Route('/addevent', name: 'addevent')]
    public function addEvent(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image_file')->getData();
    
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                    $this->addFlash('error', 'An error occurred while uploading the image.');
                    return $this->redirectToRoute('addevent'); // Redirect back to the form
                }
                $event->setimage_file($newFilename);
            }
    
            $entityManager->persist($event);
            $entityManager->flush();
            
            $this->addFlash('message', 'The event has been added successfully.');
            return $this->redirectToRoute('list-event');
        }
    
        return $this->render('event/addevent.html.twig', [
            'event_form' => $form->createView(),
        ]);
    }
    
    
    #[Route('/event_list', name:'list-event')]
    
    public function list(): Response
    {

        $events = $this->getDoctrine()->getRepository(Event::class)->findAll();

        return $this->render('event/listEvent.html.twig', [
            'events' => $events, // Update variable name to plural for clarity
        ]);
        
    }
    #[Route('/event_', name:'event')]
    
    public function listE(PaginatorInterface $paginator,Request $request): Response
    {
        
        $events = $this->getDoctrine()->getRepository(Event::class)->findAll();
        $query = $this->getDoctrine()->getRepository(Event::class)->createQueryBuilder('p');
        $pagination = $paginator->paginate
        (
            $query->getQuery(),
            $request->query->getInt('page', 1), // Numéro de page actuel, 1 par défaut
            1 // Nombre d'éléments par page
        );

        return $this->render('event/event.html.twig', ['pagination' => $pagination,
            'events' => $events, // Update variable name to plural for clarity
        ]);
        
    }
    #[Route('/edit_event/{id}', name:'edit-event')]
    public function editAction($id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = $entityManager->getRepository(Event::class)->find($id);

        if (!$event) {
            throw $this->createNotFoundException('Event not found');
        }

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image_file')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                }

                $event->setimage_file($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('list-event');
        }

        return $this->render('event/edit.html.twig', [
            'edit_form' => $form->createView(),
        ]);
    }

   

    /**
     * Deletes a event entity.
     *
     */
    

     #[Route('/delete-event/{id}',name:'delete-event')]
     public function delete_activite($id,ManagerRegistry $doctrine){
 
         $repo = $doctrine->getRepository(Event::class);
         $event = $repo->find($id);
         $em = $doctrine->getManager();
 
         $em->remove($event);
         $em->flush();
 
         return $this->redirectToRoute('list-event');
     }
}
