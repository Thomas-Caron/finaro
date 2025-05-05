<?php

declare(strict_types=1);

namespace App\Form\App\Account\Movement;

use App\DataEntity\App\Account\Movement\AccountMovementData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{TextType, NumberType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountMovementFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', NumberType::class, [
                'required' => false,
            ])
            ->add('label', TextType::class, [
                'required' => false,
            ])
            ->add('name', TextType::class)
            ->add('amount', NumberType::class)
            ->add('day', NumberType::class, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AccountMovementData::class,
            'csrf_protection' => false,
        ]);
    }
}