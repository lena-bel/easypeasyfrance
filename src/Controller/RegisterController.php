<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $em): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterForm::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user->setPassword($hasher->hashPassword($user,$user->getPassword()));
            $em->persist($user);//this basically sends the new created user to the database 
            $em->flush();//this basically sends the new created user to the database 
            return $this->redirectToRoute('app_login');
            // dd($user);
        }
        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
