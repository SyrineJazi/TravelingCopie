<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Reservation;
use App\Form\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation')]
    public function index(): Response
    {
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }
   
 #[Route("/addReservation{id}", name:"addReservation")]
   
    public function ajouterReservation(Request $request, $id)
    {


        $reservation = new Reservation();
        $event = $this->getDoctrine()->getRepository(Event::class)->findOneBy(['id' => $id]);

        $reservation->setIdevent($event);
        $eventName = $event->getName();
        $eventPrice=$event->getPrix();
        $reservation->setPrixE($eventPrice);
        $reservation->setEventName($eventName);


        $form = $this->createForm(ReservationType::class, $reservation);


        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {


            $em = $this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();
            return $this->redirectToRoute('list-reservation');
        }
        return $this->render('reservation/reservation.html.twig', array('reservation_form' => $form->createView()));

    }
    #[Route('/reservation_list', name:'list-reservation')]
    
    public function list(): Response
    {

        $reservations = $this->getDoctrine()->getRepository(Reservation::class)->findAll();

        return $this->render('reservation/list.html.twig', [
            'reservations' => $reservations, // Update variable name to plural for clarity
        ]);
        
    }
    #[Route('/delete_reservation{id}', name:'delete-R')]
    public function supprReservation($id)
    {
        $em = $this->getDoctrine()->getManager();
        $reservation = $this->getDoctrine()->getRepository(Reservation::class)->find($id);
        $em->remove($reservation);
        $em->flush();
        return $this->redirectToRoute('list-reservation');

    }
    #[Route('/edit_reservation/{id}', name:'edit-R')]
    public function modifierReservation(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $res = $em->getRepository(Reservation::class)->find($id);
        $form = $this->createForm(ReservationType::class, $res);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($res);
            $em->flush();
            return $this->redirectToRoute('list-reservation');
        }
       
        return $this->render('reservation/edit.html.twig', array('edit_form' => $form->createView()));

    }
}
