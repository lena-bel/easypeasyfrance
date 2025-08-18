<?php
namespace App\Controller\visitors;

use DateTime;
use App\Entity\ContactMessage;
use App\Form\ContactMessageForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactPageController extends AbstractController
{
    #[Route(name:'Contact', path:'/contact')]
    public function contactDisplay(
        Request $request,
        EntityManagerInterface $em
        ):Response
    {
        $contactMessage = new ContactMessage();
        $form = $this->createForm(ContactMessageForm::class, $contactMessage);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $em->persist($contactMessage);
            $contactMessage->setCreatedAt(new DateTime());
            $em->flush();
            $this->addFlash('success', "Your message was successfully submitted!");
            return $this->redirectToRoute('Contact');
        }

        return $this-> render('marketingPages/contact.html.twig',[
            "form"=>$form->createView()
        ]);

    }
}