<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterForm;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $em,MailerInterface $mailer ): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterForm::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $user->setPassword($hasher->hashPassword($user,$user->getPassword()));
            $user->setRoles(['ROLE_USER']);
            $user->setIsActive(false);
            $user->setRegistryDate(new \DateTime());

            $em->persist($user);//this basically sends the new created user to the database 
            $em->flush();//this basically sends the new created user to the database 

            $verificationCode = random_int(100000, 999999);

            $session = $request->getSession();
            $session->set('user_verification_' . $user->getId(), $verificationCode);
            $session->set('user_verification_expires_' . $user->getId(), time() + 900);

            $email = new Email();
            $email
            ->from('no-reply@easypeasyfrance.fr') 
            ->to($user->getEmail())
            ->subject('Activate your account')
            ->html('<p>Your activation code is: <strong>' . $verificationCode . '</strong></p>
                        <p>It will expire in 15 minutes.</p>');

            $mailer->send($email);
            return $this->redirectToRoute('app_verify_account', ['userId' => $user->getId()]);
            // dd($user);
        }
        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
