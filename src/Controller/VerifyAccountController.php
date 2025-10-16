<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class VerifyAccountController extends AbstractController
{
    #[Route('/verify-account/{userId}', name: 'app_verify_account')]
    public function verify(
        int $userId,
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $user = $em->getRepository(User::class)->find($userId);

        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        $error = null;

        if ($request->isMethod('POST')) {
            $submittedCode = $request->request->get('verification_code');

            $session = $request->getSession();
            $storedCode = $session->get('user_verification_' . $user->getId());
            $expiresAt = $session->get('user_verification_expires_' . $user->getId());

            if (!$storedCode || $expiresAt < time()) {
                $error = 'Your verification code has expired. Please register again.';
            } elseif ($submittedCode == $storedCode) {
                $user->setIsActive(true);
                $em->flush();

                $session->remove('user_verification_' . $user->getId());
                $session->remove('user_verification_expires_' . $user->getId());

                $this->addFlash('success', 'Your account is now activated! You can log in.');
                return $this->redirectToRoute('app_login');
            } else {
                $error = 'Invalid verification code.';
            }
        }

        return $this->render('register/verify.html.twig', [
            'user' => $user,
            'error' => $error,
        ]);
    }
}
