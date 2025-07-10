<?php
namespace App\Controller;

use App\Repository\TeamMembersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MarketingPagesController extends AbstractController
{
    #[Route('/', name:'home')]
    public function homeDisplay(){
        return $this->render('marketingPages/home.html.twig');
    }
    #[Route(path:'/about', name:"about")]
    public function aboutDisplat(TeamMembersRepository $teamMembersRepository){
        $members = $teamMembersRepository -> findAllTeamMembers();       
        //  dd($members);

        return $this->render('marketingPages/about.html.twig',[
            'members'=>$members
        ]);
    }
    #[Route(path:'/testimonials', name:'testimonials')]
    public function testimonialDisplay(){
        return $this->render('marketingPages/testimonials.html.twig');
    }

}