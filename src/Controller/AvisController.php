<?php

namespace App\Controller;

use App\Entity\Spot;
use App\Entity\Avis;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AvisController extends AbstractController
{
    /**
     * @Route("/", name="avis_list")
     */
    public function index(): Response
    {
        $avis = $this->getDoctrine()->getRepository(Avis::class)->findAll();
        return $this->render('admin/media_gallery.html.twig', ['avis' => $avis]);
    }

     /**
     * @Route("/avis/new/{spotId}", name="new_avis", methods={"GET", "POST"})
     */
    public function new(Request $request, $spotId)
    {
        $spot = $this->getDoctrine()->getRepository(Spot::class)->find($spotId);

        $avis = new Avis();
        $form = $this->createFormBuilder($avis)
            ->add('contenu', TextareaType::class)
            ->add('save', SubmitType::class, ['label' => 'Ajouter Avis'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avis = $form->getData();
            $avis->setSpot($spot);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($avis);
            $entityManager->flush();

            return $this->redirectToRoute('avis_list');
        }

        return $this->render('avis/new.html.twig', [
            'form' => $form->createView(),
            'spot' => $spot,
        ]);
    }

    /**
     * @Route("/avis/ajouter", name="ajouter_avis", methods={"POST"})
     */
    public function ajouterAvis(Request $request)
    {
        // This action is not needed if you are using Symfony forms for submission.
        // Symfony forms will handle the submission in the new action.
    }
    /**
     * @Route("/avis/{id}", name="avis_show")
     */
    public function show($id): Response
    {
        $avis = $this->getDoctrine()->getRepository(Avis::class)->find($id);

        return $this->render('avis/show.html.twig', ['avis' => $avis]);
    }
}