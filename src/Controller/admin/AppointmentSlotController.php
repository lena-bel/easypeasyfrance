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

    #[Route(path: 'admin/slot/manage/{id}', name: 'manage_booking')]
    public function manageBooking(
        int $id,
        Request $request,
        EntityManagerInterface $em,
        \Symfony\Component\Mailer\MailerInterface $mailer
    ): Response {
        return $this->render('admin/manage-appointment.html.twig');
    }
}
