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

        $existingAppointment = $appointmentRepo->findByUser($user);

        $availableSlots = $slotRepo->findBy(['isBooked' => false], ['date' => 'ASC', 'time' => 'ASC']);
        $groupedSlots = [];

        foreach ($availableSlots as $slot) {
            $dateKey = $slot->getDate()->format('Y-m-d');

            // Save the date once per day
            if (!isset($groupedSlots[$dateKey])) {
                $groupedSlots[$dateKey]['date'] = $slot->getDate();
                $groupedSlots[$dateKey]['slots'] = [];
            }

            // Add the current slot to the array of slots for that day
            $groupedSlots[$dateKey]['slots'][] = $slot;
        }
        // dd($groupedSlots);

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
}
