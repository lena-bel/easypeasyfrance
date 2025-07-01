<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MarketingPagesController extends AbstractController
{
    #[Route('/', name:'home')]
    public function homeDisplay(){
        return $this->render('home.html.twig');
    }

}