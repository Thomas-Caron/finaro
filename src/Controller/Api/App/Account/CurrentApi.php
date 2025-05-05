<?php

declare(strict_types=1);

namespace App\Controller\Api\App\Account;

use App\DataEntity\App\Account\InitializeAccountData;
use App\Entity\Account\AccountType;
use App\Entity\Account\Movement\AccountMovementType;
use App\Entity\Date\Month;
use App\Entity\Date\Year;
use App\Form\App\Account\InitializeAccountFormType;
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
        $initializeAccount = new InitializeAccountData();
        $form = $this->createForm(InitializeAccountFormType::class, $initializeAccount);
        $form->submit($request->getPayload()->all());
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->accountManager->initializeCurrentAccount($initializeAccount, $this->getUser());
            
            return $this->json([
                'success' => true,
            ], 201);
        }

        return $this->json([
            'success' => false,
            'errors' => $this->formHelper->getErrors($form),
        ], 400);
    }

    #[Route(path: '/account/current/calculate/{month}/{year}', methods: ['GET'], name: 'api_account_current_calculate')]
    public function calculate(
        #[MapEntity(expr: 'repository.findOneBySlug(month)')] Month $month,
        #[MapEntity(expr: 'repository.findOneByValue(year)')] Year $year
    ): JsonResponse {
        $currentAccount = $this->accountRepository->findOneBy([
            'owner' => $this->getUser(),
            'type' => $this->accountTypeRepository->findOneBySlug(AccountType::CURRENT),
        ]);
        $data = $this->accountCalculator->calculate($currentAccount, $month, $year);
            
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
}
