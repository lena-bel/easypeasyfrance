<?php

namespace App\Controller;

use DateTime;
use App\Entity\Appointment;
use App\Form\AppointmentForm;
use App\Entity\AppointmentSlot;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class AppointmentController extends AbstractController
{
    #[Route(path: '/appointment', name: 'appointment_index')]
    public function appointmentDisplay(
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $user = $this->getUser();
        $appointmentRepo = $em->getRepository(Appointment::class);
        $slotRepo = $em->getRepository(AppointmentSlot::class);

        $existingAppointment = $appointmentRepo->findAvailableAppointmentByUser($user);
        $availableSlots = $em->getRepository(AppointmentSlot::class)->findAvailableSlots();
        $groupedSlots = [];
        // dd($existingAppointment);
        foreach ($availableSlots as $slot) {
            $dateKey = $slot->getDate()->format('Y-m-d');

            if (!isset($groupedSlots[$dateKey])) {
                $groupedSlots[$dateKey]['date'] = $slot->getDate();
                $groupedSlots[$dateKey]['slots'] = [];
            }

            $groupedSlots[$dateKey]['slots'][] = $slot;
        }

        if ($existingAppointment) {
            return $this->render('appointmentIndex.html.twig', [
                'existingAppointment' => $existingAppointment,
                'groupedSlots' => $groupedSlots,
                'form' => null,
            ]);
        }


        $appointment = new Appointment();
        $form = $this->createForm(AppointmentForm::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slotId = $request->get('slot_id');
            $slot = $slotRepo->find($slotId);

            if (!$slot || $slot->getIsBooked()) {
                $this->addFlash('error', 'this slot is no longer available.');
                return $this->redirectToRoute('appointment_index');
            }
            $slot->setIsBooked(true);
            $appointment->setAppointmentSlot($slot);
            $appointment->setUser($user);
            $appointment->setcreatedAt(new \DateTime());
            $em->persist($appointment);
            $em->flush();

            $this->addFlash('success', 'You have successfully booked an appointment!');
            return $this->redirectToRoute('appointment_index');
        }

        return $this->render('appointmentIndex.html.twig', [
            'form' => $form->createView(),
            'existingAppointment' => null,
            'groupedSlots' => $groupedSlots,
        ]);
    }


    #[Route(path: '/appointment/cancel/{id}', name: 'appointment_cancel', methods: ['POST'])]
    public function cancelAppointment(int $id, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $appointmentRepo = $em->getRepository(Appointment::class);

        $appointment = $appointmentRepo->find($id);

        if (!$appointment || $appointment->getUser() !== $user) {
            $this->addFlash('error', 'Appointment not found.');
            return $this->redirectToRoute('appointment_index');
        }

        $slot = $appointment->getAppointmentSlot();

        if ($slot) {
            $slot->setIsBooked(false);
            // $slot->setAppointment(null); 
            $appointment->setAppointmentSlot(null);
            $em->persist($slot);
        }

        $appointment->setStatus('cancelled');
        $appointment->setUser(null);
        $em->remove($appointment);
        $em->flush();

        $this->addFlash('success', 'Your appointment has been successfully cancelled.');
        return $this->redirectToRoute('appointment_index');
    }
}
