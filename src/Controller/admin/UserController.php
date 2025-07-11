<?php

namespace App\Controller\admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin')]
class UserController extends AbstractController
{
    #[Route(path: '/users', name: 'users')]
    public function displayUsers(UserRepository $userRepository, Request $request)
    {
        $search = $request->query->get('search'); // Get ?search=something from the URL

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
    public function editUser()
    {
        return $this->render('admin/user-edit.html.twig');
    }
}
