<?php

namespace App\Form;

use App\Enum\Tags;
use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PostForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('image')
            ->add('tags', ChoiceType::class, [
                'choices' => array_combine(array_column(Tags::cases(), 'value'), Tags::cases()),
        // 'choice_label' => fn(Tags $tag) => $tag->value, 
        // 'choice_value' => fn(?Tags $tag) => $tag?->value,
        'multiple' => true,
        'expanded' => true, // select box
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
            'data_class' => Post::class,
        ]);
    }
}
