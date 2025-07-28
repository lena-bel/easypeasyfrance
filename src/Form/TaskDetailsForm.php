<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\TaskDetails;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskDetailsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('appointmentNote')
            ->add('appointmentLink')
            ->add('informationalMessage')
            ->add('externalLinks')
            ->add('documentCheckList')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('steps')
            ->add('taskId', EntityType::class, [
                'class' => Task::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TaskDetails::class,
        ]);
    }
}
