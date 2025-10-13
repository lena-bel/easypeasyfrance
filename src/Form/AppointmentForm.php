<?php

namespace App\Form;

use App\Entity\Appointment;
use App\Entity\AppointmentSlot;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointmentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reason')
            ->add('preferredDate', HiddenType::class,[
                'mapped'=>true            ])
            ->add('preferredTime', HiddenType::class,[
                'mapped'=>true            ])
            ->add('message')
            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ])
            // ->add('appointmentSlot', EntityType::class, [
            //     'class' => AppointmentSlot::class,
            //     'choice_label' => 'id',
            // ])

            ->add('confirm', SubmitType::class,[
                'label'=>'Confirm Appointment',
                'attr'=>[
                    'class'=>'pri-btn'
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
