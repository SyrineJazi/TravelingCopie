<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UsersController extends AbstractController
{
    #[Route('/users', name: 'admin_users')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findBy([], ['name' => 'asc']);
        return $this->render('admin/user/admin.html.twig', compact('users'));
    }

    #[Route('/edituser/{id}', name: 'app_edit_user')]
    public function editUser(int $id, Request $request, UserRepository $repository): Response
    {
        $user = $repository->find($id);
    
        if ($user === null) {
            throw $this->createNotFoundException('Book not found.');
        }
    
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
    
            return $this->redirectToRoute('admin_users'); // Redirect to the author list page after editing.
        }
    
        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(), // Pass the 'form' variable to the template
            'user' => $user,
        ]);
    }

    #[Route('/deleteuser/{id}', name: 'app_delete_user')]
public function delete(int $id, UserRepository $repository): Response
{
    $user = $repository->find($id);

    if ($user) {
        // Delete the author from the database.
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
    }

    return $this->redirectToRoute('admin_users');
}

    
}