<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Appointment;
use App\Entity\AppointmentSlot;
use Symfony\Component\Form\AbstractType;
use App\Repository\AppointmentSlotRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AppointmentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reason')
            ->add('message')
            ->add('appointmentSlot', EntityType::class, [
                'class' => AppointmentSlot::class,
                'choice_label' => fn(AppointmentSlot $slot) => $slot->getDate()->format('Y-m-d') . ' ' . $slot->getTime()->format('H:i'),
                'query_builder' => function (AppointmentSlotRepository $repo) {
                    return $repo->getAvailableSlotsQueryBuilder();
                },
                'choice_label' => fn(AppointmentSlot $slot) => $slot->getDate()->format('Y-m-d') . ' ' . $slot->getTime()->format('H:i'),
                'attr' => ['id' => 'appointmentSlotField'],
                'row_attr' => ['class' => 'hidden'],
            ])

            ->add('confirm', SubmitType::class, [
                'label' => 'Confirm Appointment',
                'attr' => [
                    'class' => 'pri-btn'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}
