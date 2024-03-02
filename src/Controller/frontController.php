<?php

namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Spot; // Ajoutez cette ligne pour importer la classe Spot

class frontController extends AbstractController
{private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/front', name: 'app_front')]
    public function index(Request $request): Response
    {
        
        // Récupérer les données des spots depuis la base de données
        $spots = $this->getDoctrine()->getRepository(Spot::class)->findAll();

        // Rendre le template avec les données des spots
        return $this->render('front/index.html.twig', [
            'spots' => $spots,
        ]);
}
/**
     * @Route("/front/spot/{id}", name="front_spot_details")
     */
    public function spotDetail($id)
    {
        // Récupérez le spot à partir de l'ID
        $spot = $this->getDoctrine()->getRepository(Spot::class)->find($id);

        // Récupérez les avis associés au spot
        $avis = $spot->getAvis();

        return $this->render('front/spot_detail.html.twig', ['spot' => $spot, 'avis' => $avis]);
    }

}