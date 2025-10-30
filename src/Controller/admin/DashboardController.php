<?php
namespace App\Controller\admin;

use App\Repository\AppointmentRepository;
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
        UserRepository $userRepository,
        AppointmentRepository $aptRepository
    ) :Response{
        // $user = $this->getUser();
        // dd($user);
        $totalUsersPerProfile = $userRepository->getTotalNumberPerVisaType();
        // dd($totalUsersPerProfile);
        $totalUsers= $userRepository-> getTotalNumberOfUsers();
        // dd($totalUsers);
        $totalActiveUsers = $userRepository->getTotalNumberOfActiveUsers();
        // dd($totalActiveUsers);
        $totalAppointments =$aptRepository->getAllAppointments();
        // dd($totalAppointment);
        $availableAppointments=$aptRepository->findAvailableAppointments();
        // dd($availableAppointments);
        return $this -> render('admin/dashboard.html.twig',[
            'totalUsers'=>$totalUsers,
            'totalActiveUsers'=>$totalActiveUsers,
            'totalUsersPerProfile'=>$totalUsersPerProfile,
            'totalAppointments'=>$totalAppointments,
            'availableAppointments'=>$availableAppointments
        ]);

    }
}