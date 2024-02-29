<?php

namespace App\Controller;

use App\Entity\Event;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api', name: 'app_api')]
    public function index(): Response
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
      
     #[Route("/api/{id}/edit", name:"api_event_edit", methods:"PUT")]
     
    public function majEvent(?Event $calendar, Request $request)
    {
        // On récupère les données
        $donnees = json_decode($request->getContent());

        if(
            isset($donnees->title) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start) &&
            
            isset($donnees->image_file) && !empty($donnees->image_file) &&
            isset($donnees->description) && !empty($donnees->description) &&
            isset($donnees->destination) && !empty($donnees->destination) &&
            isset($donnees->numberofdays) && !empty($donnees->numberofdays) &&
            isset($donnees->capacity) && !empty($donnees->capacity) &&
            isset($donnees->prix) && !empty($donnees->prix) &&
            isset($donnees->reserved) && !empty($donnees->reserved) &&
            isset($donnees->backgroundColor) && !empty($donnees->backgroundColor) &&
            isset($donnees->borderColor) && !empty($donnees->borderColor) &&
            isset($donnees->textColor) && !empty($donnees->textColor)
        ){
            // Les données sont complètes
            // On initialise un code
            $code = 200;

            // On vérifie si l'id existe
            if(!$calendar){
                // On instancie un rendez-vous
                $calendar = new Event;

                // On change le code
                $code = 201;
            }

            // On hydrate l'objet avec les données
            $calendar->setName($donnees->title);
            $calendar->setDate(new DateTime($donnees->start));
            $calendar->setEnd(new DateTime($donnees->end));
            $calendar->setimage_file($donnees->image_file);
            $calendar->setDescription($donnees->description);
            
            if($donnees->allDay){
                $calendar->setEnd(new DateTime($donnees->start));
            }else{
                $calendar->setEnd(new DateTime($donnees->end));
            }
            $calendar->setAllDay($donnees->allDay);
            
          
             $calendar->setDestination($donnees->destination);
             
             $calendar->setNumberofdays($donnees->numberofdays);
            $calendar->setCapacity($donnees->capacity);
            $calendar->setPrix($donnees->prix);
            $calendar->setReserved($donnees->reserved);
            
            $calendar->setBackgroundColor($donnees->backgroundColor);
            $calendar->setBorderColor($donnees->borderColor);
            $calendar->setTextColor($donnees->textColor);

            $em = $this->getDoctrine()->getManager();
            $em->persist($calendar);
            $em->flush();

            // On retourne le code
            return new Response('Ok', $code);
        }else{
            // Les données sont incomplètes
            return new Response('Données incomplètes', 404);
        }


        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
}
