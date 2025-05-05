<?php

declare(strict_types=1);

namespace App\Form\App\Simulator;

use App\DataEntity\App\Simulator\CompoundInterestData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{NumberType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompoundInterestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('initialCapital', NumberType::class)
            ->add('monthlySavings', NumberType::class)
            ->add('investmentHorizon', NumberType::class)
            ->add('interestRate', NumberType::class)
            ->add('interestPaymentInterval', NumberType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CompoundInterestData::class,
            'csrf_protection' => false,
        ]);
    }
}