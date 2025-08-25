<?php

declare(strict_types=1);

namespace App\Controller\Api\App\Account;

use App\DataEntity\App\Account\AccountData;
use App\Entity\Account\AccountType;
use App\Entity\Account\Movement\AccountMovementType;
use App\Entity\Date\Month;
use App\Entity\Date\Year;
use App\Form\App\Account\AccountFormType;
use App\Repository\Account\AccountRepository;
use App\Repository\Account\AccountTypeRepository;
use App\Repository\Account\Movement\AccountMovementRepository;
use App\Repository\Account\Movement\AccountMovementTypeRepository;
use App\Services\Calculator\Account\AccountCalculator;
use App\Services\Helper\FormHelper;
use App\Services\Manager\Account\AccountManager;
use App\Services\Manager\Label\LabelManager;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api')]
class CurrentApi extends AbstractController
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly AccountMovementRepository $accountMovementRepository,
        private readonly AccountMovementTypeRepository $accountMovementTypeRepository,
        private readonly AccountTypeRepository $accountTypeRepository,
        private readonly AccountManager $accountManager,
        private readonly LabelManager $labelManager,
        private readonly AccountCalculator $accountCalculator,
        private readonly FormHelper $formHelper
    ) {
    }

    #[Route(path: '/account/current/create', methods: ['GET'], name: 'api_account_current_create')]
    public function create(): JsonResponse
    {
        $accountType = $this->accountTypeRepository->findOneBySlug(AccountType::CURRENT);
        $account = $this->accountRepository->findOneBy([
            'owner' => $this->getUser(),
            'type' => $accountType,
        ]);

        if ($account) {
            $movementType = $this->accountMovementTypeRepository->findOneBy(['slug' => AccountMovementType::REMAINING_PREVIOUS]);
            $accountMovementInitial = $this->accountMovementRepository->findOneBy([
                'account' => $account,
                'description' => 'initial',
                'movementType' => $movementType
            ]);

            if ($accountMovementInitial) {
                return $this->json([
                    'success' => true,
                    'initialize' => false,
                ], 200);
            } else {
                return $this->json([
                    'success' => true,
                    'initialize' => true,
                ], 200);
            }
        } else {
            $this->accountManager->createCurrentAccount($this->getUser());
            $this->labelManager->createOtherLabel($this->getUser());

            return $this->json([
                'success' => true,
                'initialize' => true,
            ], 200);
        }
    }

    #[Route(path: '/account/current/initialize', methods: ['POST'], name: 'api_account_current_initialize')]
    public function initialize(Request $request): JsonResponse
    {
        $accountData = new AccountData();
        $form = $this->createForm(AccountFormType::class, $accountData);
        $form->submit($request->getPayload()->all());
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->accountManager->initializeCurrentAccount($accountData, $this->getUser());
            
            return $this->json([
                'success' => true,
            ], 201);
        }

        return $this->json([
            'success' => false,
            'errors' => $this->formHelper->getErrors($form),
        ], 400);
    }

    #[Route(path: '/account/current/check/exist/{month}/{year}', methods: ['GET'], name: 'api_account_current_check_month_exist')]
    public function checkIfMonthExist(
        #[MapEntity(expr: 'repository.findOneBySlug(month)')] Month $month,
        #[MapEntity(expr: 'repository.findOneByValue(year)')] Year $year
    ): JsonResponse
    {
        $currentAccount = $this->accountRepository->findOneBy([
            'owner' => $this->getUser(),
            'type' => $this->accountTypeRepository->findOneBySlug(AccountType::CURRENT),
        ]);
    
        $remainingPrevious = $this->accountMovementRepository->getRemainingPrevious($currentAccount, $month, $year);
        $incomes = $this->accountMovementRepository->getIncomes($currentAccount, $month, $year);
        $fixedExpenses = $this->accountMovementRepository->getFixedExpenses($currentAccount, $month, $year);
        $expenses = $this->accountMovementRepository->getExpenses($currentAccount, $month, $year);
        
        $exist = !empty($remainingPrevious) || !empty($incomes) || !empty($fixedExpenses) || !empty($expenses);

        return $this->json([
            'success' => true,
            'exist' => $exist,
        ], 200);
    }

    #[Route(path: '/account/current/calculate/remaining/{month}/{year}', methods: ['GET'], name: 'api_account_current_calculate_remaining')]
    public function calculateRemaining(
        #[MapEntity(expr: 'repository.findOneBySlug(month)')] Month $month,
        #[MapEntity(expr: 'repository.findOneByValue(year)')] Year $year
    ): JsonResponse {
        $currentAccount = $this->accountRepository->findOneBy([
            'owner' => $this->getUser(),
            'type' => $this->accountTypeRepository->findOneBySlug(AccountType::CURRENT),
        ]);
        $data = $this->accountCalculator->calculateRemaining($currentAccount, $month, $year);
            
        if ($data) {
            return $this->json([
                'success' => true,
                'data' => $data,
            ], 200);
        }

        return $this->json([
            'success' => false,
        ], 400);
    }

    #[Route(path: '/account/current/calculate/remaining/{year}', methods: ['GET'], name: 'api_account_current_calculate_remaining_by_year')]
    public function calculateRemainingByYear(
        #[MapEntity(expr: 'repository.findOneByValue(year)')] Year $year
    ): JsonResponse {
        $currentAccount = $this->accountRepository->findOneBy([
            'owner' => $this->getUser(),
            'type' => $this->accountTypeRepository->findOneBySlug(AccountType::CURRENT),
        ]);
        $data = $this->accountCalculator->calculateRemainingByYear($currentAccount, $year);
            
        if ($data) {
            return $this->json([
                'success' => true,
                'data' => $data,
            ], 200);
        }

        return $this->json([
            'success' => false,
        ], 400);
    }

    #[Route(path: '/account/current/calculate/by/label/{month}/{year}', methods: ['GET'], name: 'api_account_current_calculate_by_label')]
    public function calculateByLabel(
        #[MapEntity(expr: 'repository.findOneBySlug(month)')] Month $month,
        #[MapEntity(expr: 'repository.findOneByValue(year)')] Year $year
    ): JsonResponse {
        $currentAccount = $this->accountRepository->findOneBy([
            'owner' => $this->getUser(),
            'type' => $this->accountTypeRepository->findOneBySlug(AccountType::CURRENT),
        ]);
        $data = $this->accountCalculator->calculateByLabel($currentAccount, $month, $year);
            
        if ($data) {
            return $this->json([
                'success' => true,
                'data' => $data,
            ], 200);
        }

        return $this->json([
            'success' => false,
        ], 400);
    }

    #[Route(path: '/account/current/available/months/{year}', methods: ['GET'], name: 'api_account_current_get_months_available_by_year')]
    public function availableByYear(
        #[MapEntity(expr: 'repository.findOneByValue(year)')] Year $year
    ): JsonResponse
    {
        $currentAccount = $this->accountRepository->findOneBy([
            'owner' => $this->getUser(),
            'type' => $this->accountTypeRepository->findOneBySlug(AccountType::CURRENT),
        ]);
        $months = $this->accountMovementRepository->getMonthsWithMovementsByYear($currentAccount, $year);

        $data = array_map(fn($month) => [
            'label' => $month->getName(),
            'value' => $month->getSlug()
        ], $months);

        return $this->json([
            'success' => true,
            'data' => $data
        ], 200);
    }

    #[Route(path: '/account/current/unavailable/months/{year}', methods: ['GET'], name: 'api_account_current_get_months_unavailable_by_year')]
    public function unavailableByYear(
        #[MapEntity(expr: 'repository.findOneByValue(year)')] Year $year
    ): JsonResponse
    {
        $currentAccount = $this->accountRepository->findOneBy([
            'owner' => $this->getUser(),
            'type' => $this->accountTypeRepository->findOneBySlug(AccountType::CURRENT),
        ]);
        $months = $this->accountMovementRepository->getMonthsWithoutMovementsByYear($currentAccount, $year);

        $data = array_map(fn($month) => [
            'label' => $month->getName(),
            'value' => $month->getSlug()
        ], $months);

        return $this->json([
            'success' => true,
            'data' => $data
        ], 200);
    }

    #[Route(path: '/account/current/create/month', methods: ['POST'], name: 'api_account_current_create_month')]
    public function createMonth(Request $request): JsonResponse
    {
        $accountData = new AccountData();
        $form = $this->createForm(AccountFormType::class, $accountData);
        $form->submit($request->getPayload()->all());
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->accountManager->createMonthCurrentAccount($accountData, $this->getUser());
            
            return $this->json([
                'success' => true,
            ], 201);
        }

        return $this->json([
            'success' => false,
            'errors' => $this->formHelper->getErrors($form),
        ], 400);
    }
}
