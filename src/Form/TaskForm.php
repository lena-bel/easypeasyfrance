<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\VisaTypeProfile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TaskForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Task Title'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Task Description'
            ])
            ->add('appointmentNote', TextareaType::class, [
                'label' => 'Appointment Notes',
                'required' => false
            ])
            ->add('appointmentLink', TextType::class, [
                'label' => 'Appointment Link',
                'required' => false
            ])
            ->add('informationalMessage', TextType::class, [
                'label' => 'Informational Message',
                'required' => false
            ])

            // ->add('status')
            ->add('visaType', EntityType::class, [
                'class' => VisaTypeProfile::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Applicable Visa Types',
                'attr' => ['class' => 'checkboxes']
            ])
            ->add('steps', CollectionType::class, [
                'entry_type' => StepsForm::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'label' => 'Steps',
                'attr' => ['class' => 'collection-steps']
            ])
            ->add('documents', CollectionType::class, [
                'entry_type' => DocumentsForm::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'label' => 'Required Documents',
                'attr' => ['class' => 'collection-documents']
            ])
            ->add('links', CollectionType::class, [
                'entry_type' => LinkForm::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'label' => 'Helpful Links',
                'attr' => ['class' => 'collection-links']
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
