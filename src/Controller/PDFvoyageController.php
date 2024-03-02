<?php

namespace App\Controller;

use App\Entity\Voyage;
use App\Form\VoyageType;
use App\Repository\VoyageRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Snappy\Pdf;

class PDFvoyageController extends AbstractController
{
    #[Route('/p/d/fvoyage', name: 'app_p_d_fvoyage')]
    public function index(): Response
    {
        return $this->render('pd_fvoyage/index.html.twig', [
            'controller_name' => 'PDFvoyageController',
        ]);
    }

    private $snappy;

    public function __construct(Pdf $snappy)
    {
        $this->snappy = $snappy;
    }

    #[Route('/generate-pdf-voyage/{id}', name:'generate-pdf-voyage')]
    public function voyagePDF($id,ManagerRegistry $doctrine)
    {

        $repo = $doctrine->getRepository(Voyage::class);
        $voyage = $repo->findOneBy(['id'=> $id]);
        
        $html = $this->renderView('voyage/PDFvoyage.html.twig', [
            'voyage' => $voyage
        ]);
        $nom = $voyage->getNom();
        $date = $voyage->getDateDebut();
        $filename = $nom . '_' . $date->format('Ymd');

        return new Response(
            $this->snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachement; filename="'.$filename.'.pdf"'
            )
        );
    }
}
