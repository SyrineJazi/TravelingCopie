<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Form\EditBlogFormType;
use App\Repository\BlogRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_blog')]
    public function index(): Response
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }
   #[Route('/list-blog', name:'list-blog')]
    public function list_blog(BlogRepository $repo){
        $list_blogs=$repo->findAll();
      
    
        return $this->render('blog/list.html.twig',['blogs'=>$list_blogs]);
    }

    #[Route('/add-blog', name: 'add-blog')]
    public function addBlog(Request $request): Response
    {
        $blog = new Blog();
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $imagebFile = $form->get('imageb')->getData();
            if ($imagebFile) {
               //  $bannerFileName = md5(uniqid()) . '.' . $bannerFile->getClientOriginalName();
                 $imagebFileName =$imagebFile->getClientOriginalName();

                $imagebFile->move(
                    $this->getParameter('uploaded_images_directory'), // Directory to store uploaded banner files
                    $imagebFileName
                );
                $blog->setImageb($imagebFileName); // Set the file name to the banner property
            }
            
            $entityManager->persist($blog);
            $entityManager->flush();
            return $this->redirectToRoute('list-blog');
        }
         
        return $this->render('blog/addBlog.html.twig', [
            'blog_form' => $form->createView(),
        ]);
    }
    #[Route('/delete-blog/{id}', name: 'delete-blog')]
    public function deleteBlog(int $id): Response
    {
        // Récupérer l'entité de l'article de blog depuis la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $blog = $entityManager->getRepository(Blog::class)->find($id);

        if (!$blog) {
            throw $this->createNotFoundException('Article de blog introuvable');
        }

        // Supprimer l'article de blog
        $entityManager->remove($blog);
        $entityManager->flush();

        // Rediriger vers la liste des articles de blog ou une autre page appropriée
        return $this->redirectToRoute('list-blog');
    }
    #[Route('/edit-blog/{id}', name: 'edit-blog')]
    public function editBlog(Request $request, int $id): Response
    {
        // Récupérer l'entité de l'article de blog depuis la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $blog = $entityManager->getRepository(Blog::class)->find($id);

        if (!$blog) {
            throw $this->createNotFoundException('Article de blog introuvable');
        }

        // Créer le formulaire pour éditer l'article de blog
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        // Traiter la soumission du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $imagebFile = $form->get('imageb')->getData();
            if ($imagebFile) {
               //  $bannerFileName = md5(uniqid()) . '.' . $bannerFile->getClientOriginalName();
                 $imagebFileName =$imagebFile->getClientOriginalName();

                $imagebFile->move(
                    $this->getParameter('uploaded_images_directory'), // Directory to store uploaded banner files
                    $imagebFileName
                );
                $blog->setImageb($imagebFileName); // Set the file name to the banner property
            }
            // Persister les changements dans la base de données
            $entityManager->flush();

            // Rediriger vers la liste des articles de blog ou une autre page appropriée
            return $this->redirectToRoute('list-blog');
        }

        // Rendre le template du formulaire d'édition avec le formulaire
        return $this->render('blog/editBlog.html.twig', [  'blog' => $blog,
            'blog_form' => $form->createView(),
        ]);
    }
    #[Route('/alist-blog', name:'alist-blog')]
    public function alist_blog(BlogRepository $repo){
        $list_blogs=$repo->findAll();
      
    
        return $this->render('blog/aff.html.twig',['blogs'=>$list_blogs]);
    }
}
