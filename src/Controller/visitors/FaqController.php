<?php

namespace App\Controller\visitors;

use App\Entity\Faq;
use App\Form\FaqType;
use DateTimeImmutable;
use App\Repository\FaqRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FaqController extends AbstractController
{
    #[Route(path: '/faq', name: 'faq')]
    public function displayFaq(
        FaqRepository $repo
    ): Response {
        $faqs = $repo->getAllFaqs();
        // dd($faqs);
        return $this->render('marketingPages/faq.html.twig', [
            'faqs' => $faqs
        ]);
    }

    #[Route(path: '/admin/faq', name: 'admin_faq')]
    public function faqDisplay(
        FaqRepository $repo,
        Request $request
    ): Response {

        $search = $request->query->get('search');
        if ($search) {
            $faqs = $repo->getFaqsBySearch($search);
            // dd($faqs);

        } else {
            $faqs = $repo->getAllFaqs();
        }

        return $this->render('/admin/faq.html.twig', [
            'faqs' => $faqs
        ]);
    }

    #[Route(path: '/admin/new-faq', name: 'new_faq')]
    public function faqCreate(
        Request $request, // request contains form submission and all http request data, it tells symfony that there was a form that was submitted
        EntityManagerInterface $em // doctrine's way to write into the database
    ): Response {
        $faq = new Faq(); //creates a new object faq

        $form = $this->createForm(FaqType::class, $faq); //creates the form that is bound to the new object created 
        $form->handleRequest($request); //request data that was entered into the form and adds it to the faq object that we created earlier

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($faq); //persist is like preparing the database 
            $faq->setCreatedAt(new DateTimeImmutable());
            $em->flush(); // this is the writting of everything that was written in the form doctrine writes it into the database

            $this->addFlash('success', 'New FAQ was successfully added!');
            return $this->redirectToRoute('admin_faq');
        }

        return $this->render('admin/new-faq.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route(path: '/admin/{id}/edit', name: 'edit_faq')]
    public function editFaq(
        Faq $faq,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $form = $this->createForm(FaqType::class, $faq);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('Success', 'Faq updated successfully');
            return $this->redirectToRoute('admin_faq');
        }
        return $this->render('admin/edit-faq.html.twig', [
            "faq" => $faq,
            "form" => $form->createView()
        ]);
    }

    #[Route(path: '/admin/{id}/delete-faq', name: 'delete_faq')]
    public function deleteFaq(
        Faq $faq,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $tokenId = 'delete' . $faq->getId();
        $submittedToken = $request->request->get('_token');
        if ($this->isCsrfTokenValid($tokenId, $submittedToken)) {
            $em->remove($faq);
            $em->flush();
            $this->addFlash('success', 'task deleted successfully.');
        } else {
            $this->addFlash('error', "the task was not deleted");
        }
        return $this->redirectToRoute('admin_faq');
    }
}
