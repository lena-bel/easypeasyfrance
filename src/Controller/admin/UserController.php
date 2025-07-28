<?php

namespace App\Controller\admin;

use DateTime;
use App\Entity\User;
use App\Form\UserTypeForm;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route(path: '/admin')]
class UserController extends AbstractController
{
    #[Route(path: '/users', name: 'users')]
    public function displayUsers(UserRepository $userRepository, Request $request): Response
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
    public function editUser(Request $request, User $user, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(UserTypeForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUpdatedDate(new DateTime());
            $em->flush();
            $this->addFlash('success', 'User updated successfully.');

            return $this->redirectToRoute('users');
        }

        return $this->render('admin/user-edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/admin/users/{id}/toggle-status', name: 'user_toggle_status')]
    public function toggleStatus(User $user, EntityManagerInterface $em): Response
    {
        $user->setIsActive(!$user->isActive());

        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('users');
    }
}
