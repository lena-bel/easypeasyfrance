<?php

namespace App\Controller;

use DateTime;
use App\Entity\Appointment;
use App\Form\AppointmentForm;
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
        $appointment = new Appointment();
        $form = null;

        $form = $this->createForm(AppointmentForm::class, $appointment);
        $form->handleRequest($request);

        $existingAppointment = $em->getRepository(Appointment::class)->findOneBy([
            'user' => $user,
        ]);

        $availableSlots = [
            ['date' => new \DateTime('2025-10-06'), 'time' => '9:00'],
            ['date' => new \DateTime('2025-10-06'), 'time' => '9:30'],
            ['date' => new \DateTime('2025-10-06'), 'time' => '10:00'],
            ['date' => new \DateTime('2025-10-06'), 'time' => '10:30'],
            ['date' => new \DateTime('2025-10-06'), 'time' => '11:00'],
            ['date' => new \DateTime('2025-10-06'), 'time' => '11:30'],
            ['date' => new \DateTime('2025-10-06'), 'time' => '13:00'],
            ['date' => new \DateTime('2025-10-06'), 'time' => '13:30'],
            ['date' => new \DateTime('2025-10-06'), 'time' => '14:00'],
            ['date' => new \DateTime('2025-10-06'), 'time' => '14:30'],
            ['date' => new \DateTime('2025-10-06'), 'time' => '15:00'],
            ['date' => new \DateTime('2025-10-06'), 'time' => '15:30'],
            ['date' => new \DateTime('2025-10-06'), 'time' => '16:00'],
            ['date' => new \DateTime('2025-10-06'), 'time' => '16:30'],
            ['date' => new \DateTime('2025-10-06'), 'time' => '17:00'],
            ['date' => new \DateTime('2025-10-06'), 'time' => '17:30'],
            ['date' => new \DateTime('2025-10-06'), 'time' => '18:00'],
            ['date' => new \DateTime('2025-10-07'), 'time' => '10:00'],
            ['date' => new \DateTime('2025-10-07'), 'time' => '11:30'],
            ['date' => new \DateTime('2025-10-08'), 'time' => '14:30'],
            ['date' => new \DateTime('2025-10-08'), 'time' => '15:00'],
            ['date' => new \DateTime('2025-10-09'), 'time' => '10:00'],
            ['date' => new \DateTime('2025-10-09'), 'time' => '11:30'],
        ];

        $groupedSlots = [];
        foreach ($availableSlots as $slot) {
    $dateKey = $slot['date']->format('Y-m-d');
    $groupedSlots[$dateKey]['date'] = $slot['date'];
    $groupedSlots[$dateKey]['times'][] = $slot['time'];
}


        if (!$existingAppointment) {
            $appointment = new Appointment();
            $form = $this->createForm(AppointmentForm::class, $appointment);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $appointment->setUser($this->getUser());
                $appointment->setCreatedAt(new DateTime());

                $em->persist($appointment);
                $em->flush();
                $this->addFlash('success', 'You have successfully booked an appontment!');

                return $this->redirectToRoute('appointment_index');
            }
        }
        // dd($existingAppointment);


        // if($form->isSubmitted() && $form->isValid()){
        //     $appointment->setUser($this->getUser());
        //     $appointment->setCreatedAt(new DateTime());

        //     $em->persist($appointment);
        //     $em->flush();
        //     $this->addFlash('success', 'You have successfully booked an appontment!');

        //     return $this ->redirectToRoute('appointment_index');
        // }



        return $this->render('appointmentIndex.html.twig', [
            // 'form' => $form->createView(),
            'form' => $form ? $form->createView() : null,
            'existingAppointment' => $existingAppointment,
            'availableSlots' => $availableSlots,
            'groupedSlots' => $groupedSlots,
        ]);
    }
}
