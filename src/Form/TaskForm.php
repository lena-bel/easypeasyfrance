<?php

namespace App\Form;

use App\Entity\Task;
use App\Form\TaskDetailEditForm;
use App\Entity\VisaTypeProfile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TaskForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('appointmentNote')
            ->add('appointmentLink')
            ->add('informationalMessage')

            ->add('status')
            ->add('visaType', EntityType::class, [
                'class' => VisaTypeProfile::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Applicable Visa Types',
                'attr' => ['class' => 'checkboxes']
            ])
            ->add('taskDetails', CollectionType::class, [
                'entry_type' => TaskDetailEditForm::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true, 
            ])


            ->add('save', SubmitType::class, [
                'label' => 'Save Changes',
                'attr' => ['class' => 'btn-save']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
