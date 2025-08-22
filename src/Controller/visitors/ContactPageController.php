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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class ContactPageController extends AbstractController
{
    #[Route(name: 'Contact', path: '/contact')]
    public function contactDisplay(
        Request $request,
        EntityManagerInterface $em,
        MailerInterface $mailer
    ): Response {
        $contactMessage = new ContactMessage();
        $form = $this->createForm(ContactMessageForm::class, $contactMessage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // $data = $form->getData();

            $email = (new Email())
                ->from("no-reply@easypeasyfrance.fr")
                ->replyTo($contactMessage->getEmail())
                ->to('mlenabelise@gmail.com')
                ->subject('New Contact Form Message')
                ->text(
                    "Name: {$contactMessage->getName()}\n" .
                    "Email: {$contactMessage->getEmail()}\n" .
                    "Subject: {$contactMessage->getSubject()}\n".
                    "Message: {$contactMessage->getMessage()}"
                );
            $mailer->send($email);


            $em->persist($contactMessage);
            $contactMessage->setCreatedAt(new DateTime());
            $em->flush();
            $this->addFlash('success', "Your message was successfully submitted!");
            return $this->redirectToRoute('Contact');
        }

        return $this->render('marketingPages/contact.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
