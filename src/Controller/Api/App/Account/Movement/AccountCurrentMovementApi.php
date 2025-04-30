<?php

declare(strict_types=1);

namespace App\Controller\Api\App\Account\Movement;

use App\DataEntity\App\Account\Movement\AccountMovementCollectionData;
use App\DataEntity\App\Account\Movement\AccountMovementData;
use App\Entity\Account\AccountType;
use App\Entity\Account\Movement\AccountMovement;
use App\Entity\Date\Month;
use App\Entity\Date\Year;
use App\Form\App\Account\Movement\AccountMovementCollectionFormType;
use App\Form\App\Account\Movement\AccountMovementFormType;
use App\Repository\Account\AccountRepository;
use App\Repository\Account\AccountTypeRepository;
use App\Repository\Account\Movement\AccountMovementRepository;
use App\Services\Helper\FormHelper;
use App\Services\Manager\Account\Movement\AccountMovementManager;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api')]
class AccountCurrentMovementApi extends AbstractController
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly AccountMovementRepository $accountMovementRepository,
        private readonly AccountTypeRepository $accountTypeRepository,
        private readonly AccountMovementManager $accountMovementManager,
        private readonly FormHelper $formHelper
    ) {
    }

    #[Route(path: '/account/current/movement/remaining-previous/{month}/{year}', methods: ['GET'], name: 'api_account_current_movement_get_remaining_previous')]
    public function getRemainingPrevious(
        #[MapEntity(expr: 'repository.findOneBySlug(month)')] Month $month,
        #[MapEntity(expr: 'repository.findOneByValue(year)')] Year $year
    ): JsonResponse {
        $currentAccount = $this->accountRepository->findOneBy([
            'owner' => $this->getUser(),
            'type' => $this->accountTypeRepository->findOneBySlug(AccountType::CURRENT),
        ]);
        $remainingPrevious = $this->accountMovementRepository->getRemainingPrevious($currentAccount, $month, $year);
            
        if ($remainingPrevious) {
            $data = array_map(fn($movement) => [
                'id' => $movement->getId(),
                'name' => 'initial' === $movement->getDescription() ? 'Solde initial' : 'Restant',
                'amount' => $movement->getAmount()
            ], $remainingPrevious);

            return $this->json([
                'success' => true,
                'data' => $data,
            ], 200);
        }

        return $this->json([
            'success' => true,
            'data' => [],
        ], 200);
    }

    #[Route(path: '/account/current/movement/incomes/{month}/{year}', methods: ['GET'], name: 'api_account_current_movement_get_incomes')]
    public function getIncomes(
        #[MapEntity(expr: 'repository.findOneBySlug(month)')] Month $month,
        #[MapEntity(expr: 'repository.findOneByValue(year)')] Year $year
    ): JsonResponse {
        $currentAccount = $this->accountRepository->findOneBy([
            'owner' => $this->getUser(),
            'type' => $this->accountTypeRepository->findOneBySlug(AccountType::CURRENT),
        ]);
        $incomes = $this->accountMovementRepository->getIncomes($currentAccount, $month, $year);
            
        if ($incomes) {
            $data = array_map(fn($movement) => [
                'id' => $movement->getId(),
                'name' => $movement->getName(),
                'amount' => $movement->getAmount(),
            ], $incomes);

            return $this->json([
                'success' => true,
                'data' => $data,
            ], 200);
        }

        return $this->json([
            'success' => true,
            'data' => [],
        ], 200);
    }

    #[Route(path: '/account/current/movement/incomes/{month}/{year}', methods: ['POST'], name: 'api_account_current_movement_add_incomes')]
    public function addIncomes(
        Request $request,
        #[MapEntity(expr: 'repository.findOneBySlug(month)')] Month $month,
        #[MapEntity(expr: 'repository.findOneByValue(year)')] Year $year
    ): JsonResponse {
        $movementCollection = new AccountMovementCollectionData();
        $form = $this->createForm(AccountMovementCollectionFormType::class, $movementCollection);
        $form->submit($request->getPayload()->all());
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->accountMovementManager->addIncomes(
                $movementCollection,
                $month,
                $year,
                $this->getUser(),
                $this->accountTypeRepository->findOneBySlug(AccountType::CURRENT)
            );
            
            return $this->json([
                'success' => true,
            ], 201);
        }

        return $this->json([
            'success' => false,
            'errors' => $this->formHelper->getErrors($form),
        ], 400);
    }

    #[Route(path: '/account/current/movement/fixed-expenses/{month}/{year}', methods: ['GET'], name: 'api_account_current_movement_get_fixed_expenses')]
    public function getFixedExpenses(
        #[MapEntity(expr: 'repository.findOneBySlug(month)')] Month $month,
        #[MapEntity(expr: 'repository.findOneByValue(year)')] Year $year
    ): JsonResponse {
        $currentAccount = $this->accountRepository->findOneBy([
            'owner' => $this->getUser(),
            'type' => $this->accountTypeRepository->findOneBySlug(AccountType::CURRENT),
        ]);
        $fixedExpenses = $this->accountMovementRepository->getFixedExpenses($currentAccount, $month, $year);
            
        if ($fixedExpenses) {
            $data = array_map(fn($movement) => [
                'id' => $movement->getId(),
                // 'label' => $movement->getLabel()->getName(),
                'name' => $movement->getName(),
                'amount' => $movement->getAmount(),
                'prelevedAt' => $movement->getPrelevedAt(),
            ], $fixedExpenses);

            return $this->json([
                'success' => true,
                'data' => $data,
            ], 200);
        }

        return $this->json([
            'success' => true,
            'data' => [],
        ], 200);
    }

    #[Route(path: '/account/current/movement/fixed-expenses/{month}/{year}', methods: ['POST'], name: 'api_account_current_movement_add_fixed_expenses')]
    public function addFixedExpenses(
        Request $request,
        #[MapEntity(expr: 'repository.findOneBySlug(month)')] Month $month,
        #[MapEntity(expr: 'repository.findOneByValue(year)')] Year $year
    ): JsonResponse {
        $movementCollection = new AccountMovementCollectionData();
        $form = $this->createForm(AccountMovementCollectionFormType::class, $movementCollection);
        $form->submit($request->getPayload()->all());
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->accountMovementManager->addFixedExpenses(
                $movementCollection,
                $month,
                $year,
                $this->getUser(),
                $this->accountTypeRepository->findOneBySlug(AccountType::CURRENT)
            );
            
            return $this->json([
                'success' => true,
            ], 201);
        }

        return $this->json([
            'success' => false,
            'errors' => $this->formHelper->getErrors($form),
        ], 400);
    }

    #[Route(path: '/account/current/movement/expenses/{month}/{year}', methods: ['GET'], name: 'api_account_current_movement_get_expenses')]
    public function getExpenses(
        #[MapEntity(expr: 'repository.findOneBySlug(month)')] Month $month,
        #[MapEntity(expr: 'repository.findOneByValue(year)')] Year $year
    ): JsonResponse {
        $currentAccount = $this->accountRepository->findOneBy([
            'owner' => $this->getUser(),
            'type' => $this->accountTypeRepository->findOneBySlug(AccountType::CURRENT),
        ]);
        $expenses = $this->accountMovementRepository->getExpenses($currentAccount, $month, $year);
            
        if ($expenses) {
            $data = array_map(fn($movement) => [
                'id' => $movement->getId(),
                // 'label' => $movement->getLabel()->getName(),
                'name' => $movement->getName(),
                'amount' => $movement->getAmount(),
            ], $expenses);

            return $this->json([
                'success' => true,
                'data' => $data,
            ], 200);
        }

        return $this->json([
            'success' => true,
            'data' => [],
        ], 200);
    }

    #[Route(path: '/account/current/movement/expenses/{month}/{year}', methods: ['POST'], name: 'api_account_current_movement_add_expenses')]
    public function addExpenses(
        Request $request,
        #[MapEntity(expr: 'repository.findOneBySlug(month)')] Month $month,
        #[MapEntity(expr: 'repository.findOneByValue(year)')] Year $year
    ): JsonResponse {
        $movementCollection = new AccountMovementCollectionData();
        $form = $this->createForm(AccountMovementCollectionFormType::class, $movementCollection);
        $form->submit($request->getPayload()->all());
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->accountMovementManager->addExpenses(
                $movementCollection,
                $month,
                $year,
                $this->getUser(),
                $this->accountTypeRepository->findOneBySlug(AccountType::CURRENT)
            );
            
            return $this->json([
                'success' => true,
            ], 201);
        }

        return $this->json([
            'success' => false,
            'errors' => $this->formHelper->getErrors($form),
        ], 400);
    }

    #[Route(path: '/account/current/movement/remove/{id}', methods: ['DELETE'], name: 'api_account_current_movement_remove')]
    public function remove(AccountMovement $movement): JsonResponse
    {
        $this->accountMovementManager->remove($movement);

        return $this->json([
            'success' => true,
        ], 204);
    }

    #[Route(path: '/account/current/movement/update/{id}', methods: ['PUT'], name: 'api_account_current_movement_update')]
    public function update(Request $request, AccountMovement $movement): JsonResponse
    {
        $movementModified = new AccountMovementData();
        $form = $this->createForm(AccountMovementFormType::class, $movementModified);
        $form->submit($request->getPayload()->all());
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->accountMovementManager->update($movement, $movementModified);
            
            return $this->json([
                'success' => true,
            ], 204);
        }

        return $this->json([
            'success' => false,
            'errors' => $this->formHelper->getErrors($form),
        ], 400);
    }
}
