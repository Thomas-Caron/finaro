<?php

declare(strict_types=1);

namespace Finaro\Form\App\Simulator;

use Finaro\Entity\Simulator\CompoundInterestData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{NumberType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompoundInterestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('initialCapital', NumberType::class, [
                'label' => 'Capital initial',
                'data' => 10000,
            ])
            ->add('monthlySavings', NumberType::class, [
                'label' => 'Épargne mensuelle',
                'data' => 100,
            ])
            ->add('investmentHorizon', NumberType::class, [
                'label' => 'Horizon de placement',
                'data' => 20,
            ])
            ->add('interestRate', NumberType::class, [
                'label' => 'Taux d\'intérêt',
                'data' => 7,
            ])
            ->add('interestPaymentInterval', NumberType::class, [
                'label' => 'Intervalle de versement des intérêts',
                'data' => 12,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CompoundInterestData::class,
            'csrf_protection' => true,
        ]);
    }
}