<?php
namespace App\Controller\admin;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route(path: '/admin')]
class DashboardController extends AbstractController
{
    #[Route(path: '/', name:'admin_dashboard')]
    #[IsGranted('ROLE_USER')]
    public function adminHome(
        UserRepository $userRepository
    ) :Response{
        $totalUsers= $userRepository-> getTotalNumberOfUsers();
        $totalActiveUsers = $userRepository->getTotalNumberOfActiveUsers();
        return $this -> render('admin/dashboard.html.twig',[
            'totalUsers'=>$totalUsers,
            'totalActiveUsers'=>$totalActiveUsers
        ]);

    }
}