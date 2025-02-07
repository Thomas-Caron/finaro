<?php

declare(strict_types=1);

namespace Finaro\Twig\Components\Form\App\Simulator;

use Finaro\Entity\Simulator\CompoundInterestData;
use Finaro\Form\App\Simulator\CompoundInterestFormType;
use Finaro\Services\Calculator\Simulator\CompoundInterestCalculator;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\LiveAction;

#[AsLiveComponent('CompoundInterestDataForm', template: 'components/Form/App/Simulator/CompoundInterestDataForm.html.twig')]
class CompoundInterestDataForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    public function __construct(
        private readonly CompoundInterestCalculator $compoundInterestCalculator
    ) {
    }

    #[LiveProp]
    public CompoundInterestData $initialFormData;

    #[LiveProp]
    public array $chartData = [];

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(
            CompoundInterestFormType::class,
            $this->initialFormData
        );
    }

    #[LiveAction]
    public function calculate(): void
    {
        if (!$this->getForm()->isSubmitted()) {
            $this->submitForm();
        }

        $result = $this->compoundInterestCalculator->calculate(
            $this->initialFormData->getInitialCapital(),
            $this->initialFormData->getMonthlySavings(),
            $this->initialFormData->getInvestmentHorizon(),
            $this->initialFormData->getInterestRate(),
            $this->initialFormData->getInterestPaymentInterval()
        );

        $this->chartData = [
            'years' => array_map(fn($item) => $item['year'], $result),
            'capital' => array_map(fn($item) => $item['capital'], $result),
            'saving' => array_map(fn($item) => $item['saving'], $result),
            'interest' => array_map(fn($item) => $item['interest'], $result),
        ];
    }
}