<?php

namespace App\Controller\admin;

use App\Entity\Appointment;
use App\Entity\AppointmentSlot;
use App\Form\AppointmentSlotForm;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AppointmentSlotRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route(path: '/admin')]
class AppointmentSlotController extends AbstractController
{
    #[Route(path: '/slot', name: 'appointment_slot_index')]
    public function slotIndex(AppointmentSlotRepository $slotRepo): Response
    {
        $slots = $slotRepo->findAllOrdered();
        // dd($slots);
        return $this->render('admin/slots-index.html.twig', [
            'slots' => $slots,
        ]);
    }

    #[Route(path: '/new-slot', name: 'create_slot')]
    public function newSlot(
        Request $request,
        EntityManagerInterface $em
    ): Response {

        $slot = new AppointmentSlot();
        $form = $this->createForm(AppointmentSlotForm::class, $slot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $existingSlot = $em->getRepository(AppointmentSlot::class)->findOneBy([
                'date' => $slot->getDate(),
                'time' => $slot->getTime(),
            ]);
            if ($existingSlot) {
                return $this->redirectToRoute('appointment_slot_index');
            }

            $em->persist($slot);
            $em->flush();

            $this->addFlash('success', 'slot created successfully.');
            return $this->redirectToRoute('appointment_slot_index');
        }

        return $this->render('admin/new-slot.html.twig', [
            'form' => $form->createView(),
            'title' => 'Create New Slot',
        ]);
    }


    #[Route('/{id}/delete', name: 'slot_delete')]
    public function delete(AppointmentSlot $slot, EntityManagerInterface $em): Response
    {
        $em->remove($slot);
        $em->flush();
        $this->addFlash('success', 'Slot deleted.');
        return $this->redirectToRoute('appointment_slot_index');
    }


    #[Route('/admin/slot/{id}', name: 'appointment_info', methods: ['GET'])]
    public function showSlot(int $id, EntityManagerInterface $em): Response
    {
        $slotRepo = $em->getRepository(AppointmentSlot::class);
        $slot = $slotRepo->findSlotWithAppointment($id);
        // dd($slot);
        // dd($slot->getAppointment()->first());
        if (!$slot) {
            $this->addFlash('error', 'Slot not found.');
            return $this->redirectToRoute('appointment_info');
        }
        return $this->render('admin/appointment-info.html.twig', [
            'slot' => $slot,
            'appointment' => $slot->getAppointment()->first(),
        ]);
    }

    #[Route(path: '/admin/slot/manage/{id}', name: 'manage_booking')]
    public function manageBooking(
        int $id,
        Request $request,
        EntityManagerInterface $em,
        AppointmentSlotRepository $slotRepo,
        \Symfony\Component\Mailer\MailerInterface $mailer
    ): Response {
         $slot = $em->getRepository(AppointmentSlot::class)->find($id);

    if (!$slot) {
        throw $this->createNotFoundException('Appointment slot not found.');
    }

    
    $appointment = $slot->getAppointment()->first();

    if (!$appointment) {
        throw $this->createNotFoundException('No appointment found for this slot.');
    }

    if ($request->isMethod('POST')) {

        $messageText = $request->request->get('admin_message');

        if (!$messageText) {
            $this->addFlash('error', 'Please write a message before submitting.');
            return $this->redirectToRoute('manage_booking', ['id' => $id]);
        }

        
        $email = (new \Symfony\Component\Mime\Email())
            ->from('contact@easypeasyfrance.fr')
            ->to($appointment->getUser()->getEmail())
            ->subject('Appointment Update')
            ->text($messageText);

        $mailer->send($email);

        
        $slot->setIsBooked(false);

        
        $appointment->setAppointmentSlot(null);
        $appointment->setUser(null);

        $em->remove($appointment);
        $em->flush();

        $this->addFlash('success', 'Appointment marked as attended and user notified.');
        return $this->redirectToRoute('admin_dashboard');
    }

    
    return $this->render('admin/manage-booking.html.twig', [
        'appointment' => $appointment,
    ]);
    }
}
