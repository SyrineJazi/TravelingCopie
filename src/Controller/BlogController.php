<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Comm;
use App\Form\CommType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use App\Form\BlogType;
use App\Form\EditBlogFormType;
use Doctrine\ORM\QueryBuilder;
use App\Repository\BlogRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;

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
       
            $comms = $blog->getComms();
            foreach ($comms as $comm) {
                $entityManager->remove($comm);
            }

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
    #[Route('/blog-details/{id}', name: 'blog_details')]
    public function details(int $id, BlogRepository $blogRepository): Response
    {
        $blog = $blogRepository->find($id);

        if (!$blog) {
            throw $this->createNotFoundException('Blog not found');
        }

        $comms = $blog->getComms(); // Récupérer les commentaires associés au blog

        return $this->render('blog/details.html.twig', [
            'blog' => $blog,
            'comms' => $comms,
        ]);
    }
    #[Route('/sort_blog', name: 'sort_blog')]
    public function Sort(Request $request , BlogRepository $blogRepository): Response
    {
        $query = $request->query->get('query');
        $sort = $request->query->get('sort');
        $sort = 'ASC';
        $blogs = $blogRepository->createQueryBuilder('b');
    
       # // Ajouter une condition de recherche si un terme de recherche est fourni
       if ($query) {
       $blogs->where('b.titre LIKE :query')
                ->setParameter('query', '%'.$query.'%');
       }
    
        // Ajouter une condition de tri si un critère de tri est fourni
if ($sort) {
    // Assurez-vous d'ajouter l'ASC ou le DESC pour spécifier le sens du tri
    $blogs->orderBy('b.titre', $sort);
} if ($sort === 'date') {
    $blogs->orderBy('b.date', 'DESC'); // Supposons que vous vouliez trier par date décroissante
}

$result = $blogs->getQuery()->getResult();

return $this->render('blog/aff.html.twig', [
    'blogs' => $result,
]);

    }
   
    #[Route('/search_blog', name: 'search_blog')]
    public function searchBlog(Request $request): JsonResponse
    {
        $query = $request->query->get('q');
        
        // Effectuer la recherche dans la base de données en fonction de la requête $query
        $entityManager = $this->getDoctrine()->getManager();
        $results = $entityManager->getRepository(Blog::class)->findByTitle($query); // Exemple de méthode de recherche personnalisée
        
        // Transformer les résultats en tableau associatif pour le renvoi au format JSON
        $formattedResults = [];
        foreach ($results as $result) {
            $formattedResults[] = [
                'title' => $result->getTitle(),
                // Ajoutez d'autres champs du blog que vous souhaitez renvoyer
            ];
        }
        
        return new JsonResponse($formattedResults);
    } 
}
