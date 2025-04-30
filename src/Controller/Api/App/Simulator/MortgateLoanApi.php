<?php

declare(strict_types=1);

namespace App\Controller\Api\App\Simulator;

use App\DataEntity\App\Simulator\MortgateLoanData;
use App\Form\App\Simulator\MortgateLoanFormType;
use App\Services\Calculator\Simulator\MortgateLoanCalculator;
use App\Services\Helper\FormHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api')]
class MortgateLoanApi extends AbstractController
{
    public function __construct(
        private readonly MortgateLoanCalculator $mortgateLoanCalculator,
        private readonly FormHelper $formHelper
    ) {
    }

    #[Route(path: '/simulator/mortgate-loan/calculate', methods: ['POST'], name: 'api_simulator_mortgate_loan_calculate')]
    public function index(Request $request): JsonResponse
    {
        $mortgateLoan = new MortgateLoanData();
        $form = $this->createForm(MortgateLoanFormType::class, $mortgateLoan);
        $form->submit($request->getPayload()->all());
        
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $this->mortgateLoanCalculator->calculate($mortgateLoan);

            return $this->json([
                'success' => true,
                'data' => $data
            ], 201);
        }

        return $this->json([
            'success' => false,
            'errors' => $this->formHelper->getErrors($form),
        ], 400);
    }
}
