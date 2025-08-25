<?php

declare(strict_types=1);

namespace App\Services\Manager\Account;

use App\DataEntity\App\Account\AccountData;
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

    public function initializeCurrentAccount(AccountData $accountData, User $owner)
    {
        $accountType = $this->entityManager->getRepository(AccountType::class)->findOneBy(['slug' => AccountType::CURRENT]);
        $this->accountMovementManager->createRemainingPrevious($accountData, $owner, $accountType, true);
        $this->accountMovementManager->createIncomes($accountData, $owner, $accountType);
        $this->accountMovementManager->createFixedExpenses($accountData, $owner, $accountType);
    }

    public function createMonthCurrentAccount(AccountData $accountData, User $owner)
    {
        $accountType = $this->entityManager->getRepository(AccountType::class)->findOneBy(['slug' => AccountType::CURRENT]);
        $this->accountMovementManager->createRemainingPrevious($accountData, $owner, $accountType, false);
        $this->accountMovementManager->createIncomes($accountData, $owner, $accountType);
        $this->accountMovementManager->createFixedExpenses($accountData, $owner, $accountType);
    }

    public function flush(Account $account)
    {
        $this->entityManager->persist($account);
        $this->entityManager->flush();
    }
}
