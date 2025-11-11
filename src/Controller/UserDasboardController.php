<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
class UserDasboardController extends AbstractController
{
    #[Route('/dashboard', name: 'user_dashboard')]
    // #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        $user = $this->getUser();

        // basically double checking in case the is granted doesn't work
        if (!$user) {
            return $this->redirectToRoute('app_login'); 
        }
        // dd($user);

        return $this->render('user-dashboard.html.twig', [
            'user' => $user,
        ]);
    }
}