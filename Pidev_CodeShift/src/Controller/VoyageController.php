<?php

namespace App\Controller;

use App\Entity\Voyage;
use App\Form\VoyageType;
use App\Repository\VoyageRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class VoyageController extends AbstractController
{
    #[Route('/voyage', name: 'app_voyage')]
    public function index(): Response
    {
        return $this->render('voyage/index.html.twig', [
            'controller_name' => 'VoyageController',
        ]);
    }

    #[Route('/add-voyage', name:'add-voyage')]
    public function add_voyage(ManagerRegistry $doctrine, Request $request){
        $voyage = new Voyage();
        $em = $doctrine->getManager();

        $form = $this->createForm(VoyageType::class, $voyage);
        $form-> handleRequest($request);

        if($form->isSubmitted()){
              $em->persist($voyage);
              $em->flush(); 
              return $this->redirectToRoute('list-voyage');
        }
        return $this->render('voyage/addVoyage.html.twig',['voyage_form'=>$form->createView()]);
    }
    #[Route('/list-voyage', name:'list-voyage')]
    public function list_voyage(VoyageRepository $repo){
        $list_voyages=$repo->findAll();
        /*
        changing function in the repository would allow for specialized ways of sorting
        in cas you want to devide the trips in 2 types
        */
        return $this->render('voyage/list.html.twig',['voyages'=>$list_voyages]);
    }

    #[Route('/delete-voyage/{id}',name:'delete-voyage')]
    public function delete_voyage($id,ManagerRegistry $doctrine){

        $voyage_repo = $doctrine->getRepository(Voyage::class);
        $voyage = $voyage_repo->find($id);
        $em = $doctrine->getManager();

        $em->remove($voyage);
        $em->flush();

        return $this->redirectToRoute('list-voyage');
    }

    #[Route('/edit-voyage/{id}', name:'edit-voyage')]
    public function edit_voyage($id,ManagerRegistry $doctrine, Request $request){
        
        $em = $doctrine->getManager();
        $repo = $doctrine->getRepository(Voyage::class);
        $voyage = $repo->find($id);

        $form = $this->createForm(VoyageType::class, $voyage);
        $form-> handleRequest($request);

        if($form->isSubmitted()){
              $em->persist($voyage);
              $em->flush(); 
              return $this->redirectToRoute('list-voyage');
        }
        return $this->render('voyage/editVoyage.html.twig',['voyage_form'=>$form->createView()]);
    }































}
