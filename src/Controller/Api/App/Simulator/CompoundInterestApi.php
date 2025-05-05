<?php

declare(strict_types=1);

namespace App\Controller\Api\App\Simulator;

use App\DataEntity\App\Simulator\CompoundInterestData;
use App\Form\App\Simulator\CompoundInterestFormType;
use App\Services\Calculator\Simulator\CompoundInterestCalculator;
use App\Services\Helper\FormHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api')]
class CompoundInterestApi extends AbstractController
{
    public function __construct(
        private readonly CompoundInterestCalculator $compoundInterestCalculator,
        private readonly FormHelper $formHelper
    ) {
    }

    #[Route(path: '/simulator/compound-interest/calculate', methods: ['POST'], name: 'api_simulator_compound_interest_calculate')]
    public function index(Request $request): JsonResponse
    {
        $compoundInterest = new CompoundInterestData();
        $form = $this->createForm(CompoundInterestFormType::class, $compoundInterest);
        $form->submit($request->getPayload()->all());
        
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $this->compoundInterestCalculator->calculate($compoundInterest);

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
