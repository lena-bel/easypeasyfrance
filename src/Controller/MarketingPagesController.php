<?php

namespace App\Controller;

use App\Entity\Testimonials;
use App\Form\TestimonialForm;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TeamMembersRepository;
use App\Repository\TestimonialsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MarketingPagesController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function homeDisplay()
    {
        return $this->render('marketingPages/home.html.twig');
    }
    #[Route(path: '/about', name: "about")]
    public function aboutDisplat(TeamMembersRepository $teamMembersRepository)
    {
        $members = $teamMembersRepository->findAllTeamMembers();
        //  dd($members);

        return $this->render('marketingPages/about.html.twig', [
            'members' => $members
        ]);
    }
    #[Route(path: '/testimonials', name: 'testimonials')]
    public function testimonialDisplay(TestimonialsRepository $testimonialRepository)
    {
        $testimonials = $testimonialRepository->findAllTestimonials();
        // dd($testimonials);
        return $this->render('marketingPages/testimonials.html.twig', [
            "testimonials" => $testimonials
        ]);
    }

    #[Route(path: '/faq', name: 'faq')]
    public function faqDisplay()
    {
        return $this->render('marketingPages/faq.html.twig');
    }

    #[Route('/testimonial/new', name: 'testimonial_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Create a new testimonial instance
        $testimonial = new Testimonials();

        // Create the form linked to the testimonial entity
        $form = $this->createForm(TestimonialForm::class, $testimonial);

        // Process the current HTTP request
        $form->handleRequest($request);

        // Check if the form was submitted and is valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Link the testimonial to the currently logged-in user
            $testimonial->setUser($this->getUser());

            // Set the creation date/time to now
            $testimonial->setCreatedAt(new \DateTime());

            // Save the testimonial to the database
            $entityManager->persist($testimonial);
            $entityManager->flush();

            // Add a success message to show after redirect
            $this->addFlash('success', 'Thank you for your testimonial!');

            // Redirect to the testimonials list page (change route as needed)
            return $this->redirectToRoute('testimonials');
        }

        // Render the form template if not submitted or invalid
        return $this->render('marketingPages/new-testimonial.html.twig', [
            'testimonialForm' => $form->createView(),
        ]);
    }
}
