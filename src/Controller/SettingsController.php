<?php

namespace App\Controller;

use App\Form\SettingsForm;
use App\Form\ChangePasswordForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[IsGranted('ROLE_USER')]
class SettingsController extends AbstractController
{
    #[Route(path: '/settings', name: 'user_settings')]
    public function index(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher
    ): Response {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        // dd($user);
        // form to edit phone number and email for now that's all the user can modify
        $form = $this->createForm(SettingsForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Your information has been updated successfully!!!');
            return $this->redirectToRoute('user_settings');
        }

        //form to change password
        $passwordForm = $this->createForm(ChangePasswordForm::class);
        $passwordForm->handleRequest($request);

        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            $newPassword = $passwordForm->get('plainPassword')->getData();
            $user->setPassword($hasher->hashPassword($user, $newPassword));
            $em->flush();
            $this->addFlash('success', 'Your password has been changed successfully.');
            return $this->redirectToRoute('user_settings');
        }

        return $this->render('settings.html.twig', [
            'form' => $form->createView(),
            'passwordForm' => $passwordForm->createView(),
            'user' => $user
        ]);
    }

    #[Route(path: '/settings/delete-account', name: 'delete_account',  methods: ['POST'])]
    public function deleteAccount(
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('No user logged in.');
        }

        if (!$this->isCsrfTokenValid('delete_account', $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
        }
        $session = $request->getSession();
        $session->invalidate();

        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('app_logout');
    }
}
