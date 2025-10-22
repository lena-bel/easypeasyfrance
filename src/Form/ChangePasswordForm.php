<?php

namespace App\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePasswordForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'label' => 'Current Password:',
                'row_attr' => ['class' => 'form-div'],
                'mapped' => false, 
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Enter your current password.']),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options'  => ['label' => 'New Password:', 'row_attr' => ['class' => 'form-div'], 'label_attr' => ['id' => 'password'],],
                'second_options' => ['label' => 'Confirm New Password:', 'row_attr' => ['class' => 'form-div'], 'label_attr' => ['id' => 'password_confirm'],],
                'invalid_message' => 'Passwords must match.',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Enter a new password.']),
                    new Assert\Length([
                        'min' => 8,
                        'minMessage' => 'Password must be at least {{ limit }} characters long.',
                        'max' => 4096,
                    ]),
                    
                ],
            ]);
    }
}
