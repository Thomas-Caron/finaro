<?php

declare(strict_types=1);

namespace App\Form\App\Account\Movement;

use App\DataEntity\App\Account\Movement\AccountMovementData;
use App\Entity\Account\Movement\Enum\TransactionDirection;
use App\Entity\Label\Label;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{CheckboxType, EnumType, HiddenType, TextType, NumberType};
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
            ->add('label', EntityType::class, [
                'class' => Label::class,
                'required' => false,
            ])
            ->add('name', TextType::class, [
                'required' => false,
            ])
            ->add('amount', NumberType::class, [
                'required' => false,
            ])
            ->add('isCharged', CheckboxType::class, [
                'required' => false,
            ])
            ->add('transactionDirection', EnumType::class, [
                'class' => TransactionDirection::class,
                'choice_label' => fn($choice) => $choice->value,
                'required' => false,
            ])
            ->add('day', NumberType::class, [
                'required' => false,
            ])
             ->add('formatDay', TextType::class, [
                'mapped' => false,
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