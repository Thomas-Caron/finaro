<?php

declare(strict_types=1);

namespace App\Form\App\Label;

use App\Entity\Label\Label;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{HiddenType, TextType, NumberType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LabelFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', HiddenType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('name', TextType::class, [
                'required' => true,
            ])
            ->add('color', TextType::class, [
                'required' => true,
            ])
            ->add('updatedAt', HiddenType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('createdAt', HiddenType::class, [
                'mapped' => false,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Label::class,
            'csrf_protection' => false,
        ]);
    }
}