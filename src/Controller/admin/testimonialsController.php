<?php
namespace App\Controller\admin;

use App\Repository\TestimonialsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route(path:'/admin')]
class testimonialsController extends AbstractController
{
    #[Route(path: '/testimonials', name: 'admin-testimonials')]
    public function testimonialDisplay(TestimonialsRepository $testimonialRepository): Response
    {
        $testimonials = $testimonialRepository->findAllTestimonials();
        // dd($testimonials);
        return $this->render('admin/testimonials.html.twig', [
            "testimonials" => $testimonials
        ]);
    }
}