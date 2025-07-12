<?php

namespace App\Controller\admin;

use App\Entity\User;
use App\Form\UserTypeForm;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route(path: '/admin')]
class UserController extends AbstractController
{
    #[Route(path: '/users', name: 'users')]
    public function displayUsers(UserRepository $userRepository, Request $request)
    {
        $search = $request->query->get('search');

        if ($search) {
            $users = $userRepository->findUsersBySearch($search);
        } else {
            $users = $userRepository->findAllUsers();
        }
        // dd($users);
        return $this->render('admin/user.html.twig', [
            'users' => $users
        ]);
    }
    #[Route(path: '/users/{id}/edit', name: 'user_edit')]
    public function editUser(Request $request, User $user, EntityManagerInterface $em)
    {
        $form = $this->createForm(UserTypeForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'User updated successfully.');

            return $this->redirectToRoute('users');
        }

        return $this->render('admin/user-edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
