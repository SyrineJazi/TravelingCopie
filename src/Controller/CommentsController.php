<?php

namespace App\Controller;

use App\Entity\Comments;

use App\Form\CommentsType;
use App\Repository\CommentsRepository;
//use Egulias\EmailValidator\Parser\Comments;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\Repository;
use Doctrine\Persistence\ManagerRegistry;

class CommentsController extends AbstractController
{
    #[Route('/comments', name: 'app_comments')]
    public function index(): Response
    {
        return $this->render('comments/index.html.twig', [
            'controller_name' => 'CommentsController',
        ]);
    } #[Route('/list-comment', name:'list-comment')]
    public function list_comment(CommentsRepository $repo){
        $list_comments=$repo->findAll();
      
    
        return $this->render('comments/list.html.twig',['comments'=>$list_comments]);
    }
    #[Route('/add-comment', name:'add-comment')]
    public function add_comment( Request $request){
        $comment = new Comments();
        $form = $this->createForm(CommentsType::class, $comment);
        $form-> handleRequest($request);

        if($form->isSubmitted()&& $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
              $em->persist($comment);
              $em->flush(); 
              return $this->redirectToRoute('list-comment');
        }
        return $this->render('comments/addComments.html.twig',['comment_form'=>$form->createView()]);
    }
    #[Route('/delete-comment/{id}', name: 'delete-comment')]
    public function deleteComment(int $id): Response
    {
        // Récupérer l'entité de l'article de blog depuis la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $comment = $entityManager->getRepository(Comments::class)->find($id);

        if (!$comment) {
            throw $this->createNotFoundException('commentaire introuvable');
        }

        // Supprimer l'article de blog
        $entityManager->remove($comment);
        $entityManager->flush();

        // Rediriger vers la liste des articles de blog ou une autre page appropriée
        return $this->redirectToRoute('list-comment');
    }
    #[Route('/edit-comments/{id}', name: 'edit-comments')]
    public function editComments(Request $request, int $id): Response
    {
        // Récupérer l'entité de l'article de blog depuis la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $comments = $entityManager->getRepository(Comments::class)->find($id);

        if (!$comments) {
            throw $this->createNotFoundException('Article de blog introuvable');
        }

        // Créer le formulaire pour éditer l'article de blog
        $form = $this->createForm(CommentsType::class, $comments);
        $form->handleRequest($request);

        // Traiter la soumission du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // Persister les changements dans la base de données
            $entityManager->flush();

            // Rediriger vers la liste des articles de blog ou une autre page appropriée
            return $this->redirectToRoute('list-comment');
        }

        // Rendre le template du formulaire d'édition avec le formulaire
        return $this->render('Comments/editComments.html.twig', [  'comments' => $comments,
            'comment_form' => $form->createView(),
        ]);
    }
    }