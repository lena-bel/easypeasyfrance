<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class ForgotPasswordController extends AbstractController
{

    #[Route('/forgot-password', name: 'forgot_password')]
    public function forgotPassword(Request $request, UserRepository $userRepository, MailerInterface $mailer): Response
    {
        $error = null;
        $success = null;

        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $user = $userRepository->findOneBy(['email' => $email]);

            if (!$user) {
                $error = 'If this email exists, a code has been sent.';
            } else {
                $code = random_int(100000, 999999); 
                $session = $request->getSession();
                $session->set('forgot_password_' . $user->getId(), $code);
                $session->set('forgot_password_expires_' . $user->getId(), time() + 900); 

               
                $emailMsg = (new Email())
                    ->from('lena.mutima@easypeasyfrance.fr')
                    ->to($user->getEmail())
                    ->subject('Your password reset code')
                    ->html('<p>Your reset code is: <strong>' . $code . '</strong></p>');

                $mailer->send($emailMsg);

                $success = 'A code has been sent to your email.';
                return $this->redirectToRoute('verify_code', ['userId' => $user->getId()]);
            }
        }

        return $this->render('security/forgot-password.html.twig', [
            'error' => $error,
            'success' => $success
        ]);
    }

    #[Route('/reset-password/{userId}', name: 'app_reset_password')]
    public function resetPassword(
        int $userId,
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em
    ): Response {
         $user = $userRepository->find($userId);
    $error = null;

    if (!$user) {
        throw $this->createNotFoundException('User not found.');
    }

    
    $session = $request->getSession();
    $verified = $session->get('forgot_password_verified_' . $user->getId());
    if (!$verified) {
        return $this->redirectToRoute('verify_code', ['userId' => $user->getId()]);
    }

    if ($request->isMethod('POST')) {
        $newPassword = $request->request->get('password');
        $passwordConfirm = $request->request->get('password_confirm');

        if ($newPassword !== $passwordConfirm) {
            $error = 'Passwords do not match.';
        } elseif (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,}$/', $newPassword)) {
            $error = 'Password must be at least 8 characters, with letters, numbers, and a special character.';
        } else {
            $user->setPassword($hasher->hashPassword($user, $newPassword));
            $em->flush();

           
            $session->remove('forgot_password_' . $user->getId());
            $session->remove('forgot_password_expires_' . $user->getId());
            $session->remove('forgot_password_verified_' . $user->getId());

            $this->addFlash('success', 'Password reset successfully. You can now log in.');
            return $this->redirectToRoute('app_login');
        }
    }

    return $this->render('security/reset-password.html.twig', [
        'user' => $user,
        'error' => $error
    ]);
    }
}
