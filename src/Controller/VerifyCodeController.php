<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VerifyCodeController extends AbstractController
{
    #[Route('/verify-code/{userId}', name: 'verify_code')]
    public function verifyCode(int $userId, Request $request, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($userId);
        $error = null;

        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        if ($request->isMethod('POST')) {
            $submittedCode = $request->request->get('verification_code');

            $session = $request->getSession();
            $storedCode = $session->get('forgot_password_' . $user->getId());
            $expiresAt = $session->get('forgot_password_expires_' . $user->getId());

            if (!$storedCode || $expiresAt < time()) {
                $error = 'Your code has expired. Please request a new one.';
            } elseif ($submittedCode != $storedCode) {
                $error = 'Invalid code. Try again.';
            } else {
                 $session->set('forgot_password_verified_' . $user->getId(), true);
                return $this->redirectToRoute('app_reset_password', ['userId' => $user->getId()]);
            }
        }

        return $this->render('security/verify.html.twig', [
            'user' => $user,
            'error' => $error
        ]);
    }
}
