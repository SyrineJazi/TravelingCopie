<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Activite;
use App\Form\ActiviteType;
use App\Repository\ActiviteRepository;
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

        if($form->isSubmitted()){
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

        if($form->isSubmitted()){
              $em->persist($activite);
              $em->flush(); 
              return $this->redirectToRoute('list-activite');
        }
        return $this->render('activite/editActivite.html.twig',['activite_form'=>$form->createView()]);
    }



















































































}
