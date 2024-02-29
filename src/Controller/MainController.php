<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function calendrier(EventRepository $calendar): Response
    {
        $events=$calendar->findAll();
        $rdvs = [];

        foreach($events as $event){
            $rdvs[] = [
                'id' => $event->getId(),
                'title' => $event->getName(),
                'description' => $event->getDescription(),
                'capacity' => $event->getCapacity(),
                'reserved' => $event->getReserved(),
                'start' => $event->getDate()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'allDay' => $event->isAllDay(),
                'numberofdays' => $event->getNumberofdays(),
                'prix' => $event->getPrix(),
                'destination' => $event->getDestination(),
                'image_file' => $event->getimage_file(),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBorderColor(),
                'textColor' => $event->getTextColor(),
            ];
        }

        $data = json_encode($rdvs);
        return $this->render('main/index.html.twig',  compact('data'));
    }
}
