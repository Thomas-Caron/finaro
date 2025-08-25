<?php

declare(strict_types=1);

namespace App\Form\App\Account;

use App\DataEntity\App\Account\AccountData;
use App\Form\App\Account\Movement\AccountMovementFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{CollectionType, NumberType, TextType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('remainingPrevious', CollectionType::class, [
                'entry_type' => AccountMovementFormType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('incomes', CollectionType::class, [
                'entry_type' => AccountMovementFormType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('fixedExpenses', CollectionType::class, [
                'entry_type' => AccountMovementFormType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('month', TextType::class)
            ->add('year', NumberType::class);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AccountData::class,
            'csrf_protection' => false,
        ]);
    }
}