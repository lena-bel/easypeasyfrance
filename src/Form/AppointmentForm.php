<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Appointment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AppointmentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reason')
            ->add('preferredDate', HiddenType::class, [
                'mapped' => true,
            ])
            ->add('preferredTime', HiddenType::class, [
                'mapped' => true,
            ])
            ->add('message')
            ->add('confirm', SubmitType::class, [
                'label' => 'Confirm Appointment',
                'attr' => [
                    'class' => 'pri-btn',
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
