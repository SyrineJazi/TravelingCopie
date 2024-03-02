<?php

namespace App\Controller;

use App\Entity\Voyage;
use App\Form\VoyageType;
use App\Repository\VoyageRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
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

    #[Route('/admin', name: 'admin')]
    public function admin(): Response
    {
        return $this->render('admin/FormTemplates.html.twig');
    }
    
    /*#[Route('/front', name: 'front')]
    public function front(): Response
    {
        return $this->render('front/listVoyages.html.twig');
    }*/
    #[Route('/add-voyage', name:'add-voyage')]
    public function add_voyage(ManagerRegistry $doctrine, Request $request){
        $voyage = new Voyage();
        $em = $doctrine->getManager();

        $form = $this->createForm(VoyageType::class, $voyage);
        $form-> handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
                // Handle the image upload
                $image = $form->get('image1')->getData();

                if ($image) {
                    $newFilename = uniqid().'.'.$image->guessExtension();

                    // Move the file to the directory where you want to store it
                    $image->move(
                        $this->getParameter('kernel.project_dir').'/public/images',
                        $newFilename
                    );

                    // Save the file path to the entity
                    $voyage->setImage1($newFilename);
                }

              $em->persist($voyage);
              $em->flush(); 
              return $this->redirectToRoute('list-voyage');
        }
        return $this->render('voyage/addVoyage.html.twig',['voyage_form'=>$form->createView()]);
    }
    #[Route('/edit-voyage/{id}', name:'edit-voyage')]
    public function edit_voyage($id,ManagerRegistry $doctrine, Request $request){
        
        $em = $doctrine->getManager();
        $repo = $doctrine->getRepository(Voyage::class);
        $voyage = $repo->find($id);

        $form = $this->createForm(VoyageType::class, $voyage);
        $form-> handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // Handle the image upload
            $new_image = $form->get('image1')->getData();

            if ($new_image) {
                $newFilename = uniqid().'.'.$new_image->guessExtension();

                // Move the file to the directory where you want to store it
                $new_image->move(
                    $this->getParameter('kernel.project_dir').'/public/images',
                    $newFilename
                );

                // Save the file path to the entity
                $voyage->setImage1($newFilename);
            }

          $em->persist($voyage);
          $em->flush(); 
          return $this->redirectToRoute('list-voyage');
    }
        return $this->render('voyage/editVoyage.html.twig',['voyage_form'=>$form->createView()]);
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
    #[Route('/list-voyage', name:'list-voyage')]
    public function list_voyage(VoyageRepository $repo){
        $list_voyages=$repo->findAll();
        /*
        changing function in the repository would allow for specialized ways of sorting
        in cas you want to devide the trips in 2 types
        */
        return $this->render('voyage/list.html.twig',['voyages'=>$list_voyages]);
    }

    #[Route('/list-voyage-front', name:'list-voyage-front')]
    public function list_voyage_front(VoyageRepository $repo, Request $request, PaginatorInterface $paginator){
        //$list_voyages=$repo->findAll();
        $pagination  = $paginator->paginate(
                $repo -> paginationQuery(),
                $request->query->get('page',1),
                6
        );
        return $this->render('front/listVoyages.html.twig',[
            //'voyages'=>$list_voyages
            'pagination' => $pagination
        ]);
    }

    #[Route('/voyage-details/{id}', name:'voyage-details')]
    public function author_details($id,ManagerRegistry $doctrine){
        
        $repo = $doctrine->getRepository(Voyage::class);
        $voyage = $repo->findOneBy(['id'=> $id]);

        return $this->render('voyage/voyageDetails.html.twig',['voyage'=>$voyage]);
    }

    #[Route('/voyage-details-front/{id}', name:'voyage-details-front')]
    public function author_details_front($id,ManagerRegistry $doctrine){
        
        $repo = $doctrine->getRepository(Voyage::class);
        $voyage = $repo->findOneBy(['id'=> $id]);

        return $this->render('front/singlePageVoyage.html.twig',['voyage'=>$voyage]);
    }

    #[Route('/search-voyage', name:'search-voyage')]
    public function search(Request $request, VoyageRepository $repo)
{
    $query = $request->query->get('query');

    // Perform your search logic, for example using Doctrine or any other method

    // Assuming you have some function to get voyages based on the query
    $voyages = $repo ->findByQuery($query);

    return $this->render('voyage/listSearch.html.twig', [
        'voyages' => $voyages,
        'query' => $query,
    ]);
}

    
    

    

    




















}
