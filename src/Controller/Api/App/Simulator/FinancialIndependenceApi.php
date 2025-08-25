<?php

declare(strict_types=1);

namespace App\Controller\Api\App\Simulator;

use App\DataEntity\App\Simulator\FinancialIndependenceData;
use App\Form\App\Simulator\FinancialIndependenceFormType;
use App\Services\Calculator\Simulator\FinancialIndependenceCalculator;
// use App\Services\Calculator\Simulator\FinancialIndependenceCalculator;
use App\Services\Calculator\Simulator\TestCalculator;
use App\Services\Helper\FormHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api')]
class FinancialIndependenceApi extends AbstractController
{
    public function __construct(
        private readonly FinancialIndependenceCalculator $financialIndependenceCalculator,
        private readonly FormHelper $formHelper
    ) {
    }

    #[Route(path: '/simulator/financial-independence/calculate', methods: ['POST'], name: 'api_simulator_financial_independence_calculate')]
    public function index(Request $request): JsonResponse
    {
        $financialIndependence = new FinancialIndependenceData();
        $form = $this->createForm(FinancialIndependenceFormType::class, $financialIndependence);
        $form->submit($request->getPayload()->all());
        
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $this->financialIndependenceCalculator->calculate($financialIndependence);

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
