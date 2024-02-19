<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Activite;
use App\Entity\Voyage;
use App\Form\ActiviteType;
use App\Repository\ActiviteRepository;
use App\Repository\VoyageRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class ActiviteController extends AbstractController
{
    #[Route('/activite', name: 'app_activite')]
    public function index(): Response
    {
        return $this->render('activite/index.html.twig', [
            'controller_name' => 'ActiviteController',
        ]);
    }

    #[Route('/add-activite', name:'add-activite')]
    public function add_activite(ManagerRegistry $doctrine, Request $request){
        $activite = new Activite();
        $em = $doctrine->getManager();

        $form = $this->createForm(ActiviteType::class, $activite);
        $form-> handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
              $em->persist($activite);
              $em->flush(); 
              return $this->redirectToRoute('list-activite');
        }
        return $this->render('activite/addActivite.html.twig',['activite_form'=>$form->createView()]);
    }
    #[Route('/list-activite', name:'list-activite')]
    public function list_activite(ActiviteRepository $repo){
        $list_activite=$repo->findAll();
        /*
        changing function in the repository would allow for specialized ways of sorting
        in cas you want to devide the trips in 2 types
        */
        return $this->render('activite/list.html.twig',['activites'=>$list_activite]);
    }
    #[Route('/delete-activite/{id}',name:'delete-activite')]
    public function delete_activite($id,ManagerRegistry $doctrine){

        $activite_repo = $doctrine->getRepository(Activite::class);
        $activite = $activite_repo->find($id);
        $em = $doctrine->getManager();

        $em->remove($activite);
        $em->flush();

        return $this->redirectToRoute('list-activite');
    }

    #[Route('/edit-activite/{id}', name:'edit-activite')]
    public function edit_activite($id,ManagerRegistry $doctrine, Request $request){
        
        $em = $doctrine->getManager();
        $repo = $doctrine->getRepository(Activite::class);
        $activite = $repo->find($id);

        $form = $this->createForm(ActiviteType::class, $activite);
        $form-> handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
              $em->persist($activite);
              $em->flush(); 
              return $this->redirectToRoute('list-activite');
        }
        return $this->render('activite/editActivite.html.twig',['activite_form'=>$form->createView()]);
    }

    /* -------------------------Connecting the entities-------------------------------------------------------*/

    #[Route('/add-activite/{id}', name:'add-activite-au-voyage')]
    public function add_activite_au_voyage($id,ManagerRegistry $doctrine, Request $request){
        $activite = new Activite();
        $voyage_repo = $doctrine->getRepository(Voyage::class);
        $em = $doctrine->getManager();

        $voyage = $voyage_repo->find($id);

        $form = $this->createForm(ActiviteType::class, $activite);
        $form-> handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
              $em->persist($activite);
              $voyage->addActivite($activite);
              $voyageID = $voyage->getId();
              $em->flush(); 
              return $this->redirectToRoute('voyage-details',['id'=>$voyageID]);
        }
        return $this->render('activite/addActivite.html.twig',['activite_form'=>$form->createView()]);
    }

    #[Route('/list-activite/{id}', name:'list-activite-voyage')]
    public function list_activite_voyage($id,VoyageRepository $repo){
        $voyage = $repo->find($id);
        $list_activites = $voyage->getActivites();
        return $this->render('voyage/voyageDetails.html.twig',['activites'=>$list_activites]);
    }


















































































}
