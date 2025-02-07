<?php

declare(strict_types=1);

namespace Finaro\Twig\Components\Form\App\Simulator;

use Finaro\Entity\Simulator\MortgateLoanData;
use Finaro\Form\App\Simulator\MortgateLoanFormType;
use Finaro\Services\Calculator\Simulator\MortgateLoanCalculator;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\LiveAction;

#[AsLiveComponent('MortgateLoanDataForm', template: 'components/Form/App/Simulator/MortgateLoanDataForm.html.twig')]
class MortgateLoanDataForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    public function __construct(
        private readonly MortgateLoanCalculator $mortgateLoanCalculator
    ) {
    }

    #[LiveProp]
    public MortgateLoanData $initialFormData;

    #[LiveProp]
    public array $chartData = [];

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(
            MortgateLoanFormType::class,
            $this->initialFormData
        );
    }

    #[LiveAction]
    public function calculate(): void
    {
        if (!$this->getForm()->isSubmitted()) {
            $this->submitForm();
        }

        $result = $this->mortgateLoanCalculator->calculate(
            $this->initialFormData->getAmountBorrowed(),
            $this->initialFormData->getRepaymentPeriod(),
            $this->initialFormData->getInterestRate(),
            $this->initialFormData->getInsuranceRate()
        );

        $this->chartData = $result;
    }
}