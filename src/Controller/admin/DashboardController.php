<?php
namespace App\Controller\admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin')]
class DashboardController extends AbstractController
{
    #[Route(path: '/', name:'home-admin')]
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