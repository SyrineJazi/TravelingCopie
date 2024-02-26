<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Reservation;
use App\Form\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;
use Mediumart\Orange\SMS\SMS;
use Mediumart\Orange\SMS\Http\SMSClient;
use Symfony\Component\Mailer\MailerInterface;

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
   
    public function ajouterReservation(Request $request, $id, MailerInterface $mailer)
    {


        $reservation = new Reservation();
        $event = $this->getDoctrine()->getRepository(Event::class)->findOneBy(['id' => $id]);

        $reservation->setIdevent($event);
        $eventName = $event->getName();
        $eventPrice=$event->getPrix();
        $eventCapacity=$event->getCapacity();
        $reservation->setPrixE($eventPrice);
        $reservation->setEventName($eventName);

       

        $form = $this->createForm(ReservationType::class, $reservation);


        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
            $nbrPlacesReservees = $reservation->getNbrreservation();
            $prixTotal=$nbrPlacesReservees * $eventPrice;
            $nouvelleCapacite = $eventCapacity - $nbrPlacesReservees;
            $event-> setCapacity($nouvelleCapacite);
            $reservation->setPrixTotal($prixTotal);
            $em->persist($event);
            $em->persist($reservation);
            $em->flush();
           
            $email = (new Email())
            ->from('Majdoub.Syrine@esprit.tn')
            ->to('Majdoub.syrine@esprit.tn')
        
            ->subject('Confirmation de Reservation')
            ->html("<p>bonjour,". $reservation->getEventName()."</p><p> Votre reservation est confirmee </p>");


        $mailer->send($email);
            
            return $this->redirectToRoute('event');

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
