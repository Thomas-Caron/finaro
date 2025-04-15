<?php

declare(strict_types=1);

namespace App\Controller\Api\App\Simulator;

use App\Entity\Simulator\CompoundInterestData;
use App\Form\App\Simulator\CompoundInterestFormType;
use App\Services\Calculator\Simulator\CompoundInterestCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api')]
class CompoundInterestApi extends AbstractController
{
    public function __construct(
        private readonly CompoundInterestCalculator $compoundInterestCalculator
    ) {
    }

    #[Route(path: '/simulator/calculateCompoundInterest', methods: ['POST'], name: 'api_simulator_compound_interest')]
    public function index(Request $request): JsonResponse
    {
        $compoundInterest = new CompoundInterestData();
        $form = $this->createForm(CompoundInterestFormType::class, $compoundInterest);
        $form->submit($request->getPayload()->all());
        
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $this->compoundInterestCalculator->calculate($compoundInterest);

            return new JsonResponse([
                'success' => true,
                'data' => $data
            ], 201);
        }

        return new JsonResponse([
            'success' => false,
            'errors' => $this->getFormErrors($form),
        ], 400);
    }

    private function getFormErrors(FormInterface $form): array
    {
        $errors = [];

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = [];

                foreach ($child->getErrors(true) as $error) {
                    $errors[$child->getName()][] = $error->getMessage();
                }
            }
        }

        return $errors;
    }
}
