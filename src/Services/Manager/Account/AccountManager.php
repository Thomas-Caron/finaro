<?php

declare(strict_types=1);

namespace App\Services\Manager\Account;

use App\DataEntity\App\Account\InitializeAccountData;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Account\Account;
use App\Entity\Account\AccountType;
use App\Entity\User\User;
use App\Services\Manager\Account\Movement\AccountMovementManager;

class AccountManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly AccountMovementManager $accountMovementManager
    ) {
    }

    public function createCurrentAccount(User $owner): Account
    {
        $accountTypeRepository = $this->entityManager->getRepository(AccountType::class);
        $type = $accountTypeRepository->findOneBySlug(AccountType::CURRENT);

        $account = new Account();
        $account->setType($type);
        $account->setOwner($owner);

        $this->flush($account);

        return $account;
    }

    public function createCommonAccount(User $owner): Account
    {
        $accountTypeRepository = $this->entityManager->getRepository(AccountType::class);
        $type = $accountTypeRepository->findOneBySlug(AccountType::COMMON);

        $account = new Account();
        $account->setType($type);
        $account->setOwner($owner);

        $this->flush($account);

        return $account;
    }

    public function initializeCurrentAccount(InitializeAccountData $initializeAccountData, User $owner)
    {
        $accountType = $this->entityManager->getRepository(AccountType::class)->findOneBy(['slug' => AccountType::CURRENT]);
        $this->accountMovementManager->initializeStartingBalance($initializeAccountData, $owner, $accountType);
        $this->accountMovementManager->initializeIncomes($initializeAccountData, $owner, $accountType);
        $this->accountMovementManager->initializeFixedExpenses($initializeAccountData, $owner, $accountType);
    }

    public function flush(Account $account)
    {
        $this->entityManager->persist($account);
        $this->entityManager->flush();
    }
}
