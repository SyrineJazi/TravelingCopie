<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Form\CommentsType;
use App\Repository\CommentsRepository;
use Egulias\EmailValidator\Parser\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentsController extends AbstractController
{
    #[Route('/comments', name: 'app_comments')]
    public function index(): Response
    {
        return $this->render('comments/index.html.twig', [
            'controller_name' => 'CommentsController',
        ]);
    } #[Route('/list-comment', name:'list-comment')]
    public function list_blog(CommentsRepository $repo){
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
        return $this->render('Comments/addComments.html.twig',['comment_form'=>$form->createView()]);
    }
}
