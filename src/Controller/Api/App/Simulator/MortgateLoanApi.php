<?php

declare(strict_types=1);

namespace App\Controller\Api\App\Simulator;

use App\Entity\Simulator\MortgateLoanData;
use App\Form\App\Simulator\MortgateLoanFormType;
use App\Services\Calculator\Simulator\MortgateLoanCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api')]
class MortgateLoanApi extends AbstractController
{
    public function __construct(
        private readonly MortgateLoanCalculator $mortgateLoanCalculator
    ) {
    }

    #[Route(path: '/simulator/calculateMortgateLoan', methods: ['POST'], name: 'api_simulator_mortgate_loan')]
    public function index(Request $request): JsonResponse
    {
        $mortgateLoan = new MortgateLoanData();
        $form = $this->createForm(MortgateLoanFormType::class, $mortgateLoan);
        $form->submit($request->getPayload()->all());
        
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $this->mortgateLoanCalculator->calculate($mortgateLoan);

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
