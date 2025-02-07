<?php

declare(strict_types=1);

namespace Finaro\Form\App\Simulator;

use Finaro\Entity\Simulator\MortgateLoanData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{NumberType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MortgateLoanFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amountBorrowed', NumberType::class, [
                'label' => 'Montant emprunté',
                'data' => 200000,
            ])
            ->add('repaymentPeriod', NumberType::class, [
                'label' => 'Durée de remboursement',
                'data' => 20,
            ])
            ->add('interestRate', NumberType::class, [
                'label' => 'Taux d\'intérêt',
                'data' => 3.5,
            ])
            ->add('insuranceRate', NumberType::class, [
                'label' => 'Taux d\'asssurance',
                'data' => 0.15,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MortgateLoanData::class,
            'csrf_protection' => true,
        ]);
    }
}