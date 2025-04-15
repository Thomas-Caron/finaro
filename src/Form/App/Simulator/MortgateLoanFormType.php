<?php

declare(strict_types=1);

namespace App\Form\App\Simulator;

use App\Entity\Simulator\MortgateLoanData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{NumberType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MortgateLoanFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amountBorrowed', NumberType::class)
            ->add('repaymentPeriod', NumberType::class)
            ->add('interestRate', NumberType::class)
            ->add('insuranceRate', NumberType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MortgateLoanData::class,
            'csrf_protection' => false,
        ]);
    }
}