<?php

declare(strict_types=1);

namespace App\Form\App\Simulator;

use App\DataEntity\App\Simulator\FinancialIndependenceData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{CheckboxType, NumberType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FinancialIndependenceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currentHeritage', NumberType::class)
            ->add('initialAllocation', NumberType::class)
            ->add('monthInvestment', NumberType::class)
            ->add('investmentAllocation', NumberType::class)
            ->add('savingYears', NumberType::class)
            ->add('stockYield', NumberType::class)
            ->add('otherYield', NumberType::class)
            ->add('withdrawalRate', NumberType::class)
            ->add('inflationRate', NumberType::class)
            ->add('stockTax', NumberType::class, [
                'required' => false,
            ])
            ->add('otherTax', NumberType::class, [
                'required' => false,
            ])
            ->add('withTax', CheckboxType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FinancialIndependenceData::class,
            'csrf_protection' => false,
        ]);
    }
}