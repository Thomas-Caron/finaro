<?php

declare(strict_types=1);

namespace App\Services\Manager\Account\Movement;

use App\DataEntity\App\Account\InitializeAccountData;
use App\DataEntity\App\Account\Movement\AccountMovementCollectionData;
use App\DataEntity\App\Account\Movement\AccountMovementData;
use App\Entity\Account\Account;
use App\Entity\Account\AccountType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Account\Movement\AccountMovement;
use App\Entity\Account\Movement\AccountMovementType;
use App\Entity\Date\Month;
use App\Entity\Date\Year;
use App\Entity\Label\Label;
use App\Entity\User\User;
use App\Services\Helper\DateHelper;

class AccountMovementManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly DateHelper $dateHelper
    ) {
    }

    public function createAccountMovementRemainingPrevious(AccountMovement $accountMovement): AccountMovement
    {
        $accountMovement->setMovementType($this->entityManager->getRepository(AccountMovementType::class)->findOneBy(['slug' => AccountMovementType::REMAINING_PREVIOUS]));

        $this->flush($accountMovement);

        return $accountMovement;
    }

    public function createAccountMovementIncomes(AccountMovement $accountMovement): AccountMovement
    {
        $accountMovement->setMovementType($this->entityManager->getRepository(AccountMovementType::class)->findOneBy(['slug' => AccountMovementType::INCOMES]));

        $this->flush($accountMovement);

        return $accountMovement;
    }

    public function createAccountMovementFixedExpenses(AccountMovement $accountMovement): AccountMovement
    {
        $accountMovement->setMovementType($this->entityManager->getRepository(AccountMovementType::class)->findOneBy(['slug' => AccountMovementType::FIXED_EXPENSES]));

        $this->flush($accountMovement);

        return $accountMovement;
    }

    public function createAccountMovementExpenses(AccountMovement $accountMovement): AccountMovement
    {
        $accountMovement->setMovementType($this->entityManager->getRepository(AccountMovementType::class)->findOneBy(['slug' => AccountMovementType::EXPENSES]));

        $this->flush($accountMovement);

        return $accountMovement;
    }

    public function initializeStartingBalance(InitializeAccountData $initializeAccountData, User $owner, AccountType $accountType)
    {
        $accountMovement = new AccountMovement();
        $accountMovement->setAmount($initializeAccountData->getStartingBalance());
        $accountMovement->setDescription('initial');
        $accountMovement->setAccount(
            $this->entityManager->getRepository(Account::class)->findOneBy([
                'owner' => $owner,
                'type' => $accountType,
            ])
        );
        $accountMovement->setLabel(
            $this->entityManager->getRepository(Label::class)->findOneBy([
                'createdBy' => $owner,
                'slug' => Label::OTHER
            ])
        );
        $accountMovement->setYear($this->entityManager->getRepository(Year::class)->findOneByValue($initializeAccountData->getYear()));
        $accountMovement->setMonth($this->entityManager->getRepository(Month::class)->findOneBySlug($initializeAccountData->getMonth()));
        
        $this->createAccountMovementRemainingPrevious($accountMovement);
    }

    public function addIncomes(
        AccountMovementCollectionData $movementCollection,
        Month $month,
        Year $year,
        User $owner,
        AccountType $accountType
    ) {
        foreach ($movementCollection->getCollection() as $movement) {
            $accountMovement = new AccountMovement();
            $accountMovement->setName($movement->getName());
            $accountMovement->setAmount($movement->getAmount());
            $accountMovement->setAccount(
                $this->entityManager->getRepository(Account::class)->findOneBy([
                    'owner' => $owner,
                    'type' => $accountType,
                ])
            );
            $accountMovement->setLabel(
                $this->entityManager->getRepository(Label::class)->findOneBy([
                    'createdBy' => $owner,
                    'slug' => Label::OTHER
                ])
            );
            $accountMovement->setYear($year);
            $accountMovement->setMonth($month);
            
            $this->createAccountMovementIncomes($accountMovement);
        }
    }

    public function initializeIncomes(InitializeAccountData $initializeAccountData, User $owner, AccountType $accountType)
    {
        foreach ($initializeAccountData->getIncomes() as $movement) {
            $accountMovement = new AccountMovement();
            $accountMovement->setName($movement->getName());
            $accountMovement->setAmount($movement->getAmount());
            $accountMovement->setAccount(
                $this->entityManager->getRepository(Account::class)->findOneBy([
                    'owner' => $owner,
                    'type' => $accountType,
                ])
            );
            $accountMovement->setLabel(
                $this->entityManager->getRepository(Label::class)->findOneBy([
                    'createdBy' => $owner,
                    'slug' => Label::OTHER
                ])
            );
            $accountMovement->setYear($this->entityManager->getRepository(Year::class)->findOneByValue($initializeAccountData->getYear()));
            $accountMovement->setMonth($this->entityManager->getRepository(Month::class)->findOneBySlug($initializeAccountData->getMonth()));
            
            $this->createAccountMovementIncomes($accountMovement);
        }
    }

    public function addFixedExpenses(
        AccountMovementCollectionData $movementCollection,
        Month $month,
        Year $year,
        User $owner,
        AccountType $accountType
    ) {
        foreach ($movementCollection->getCollection() as $movement) {
            $accountMovement = new AccountMovement();
            $accountMovement->setName($movement->getName());
            $accountMovement->setAmount($movement->getAmount());
            $accountMovement->setAccount(
                $this->entityManager->getRepository(Account::class)->findOneBy([
                    'owner' => $owner,
                    'type' => $accountType,
                ])
            );
            $accountMovement->setLabel(
                $this->entityManager->getRepository(Label::class)->findOneBy([
                    'createdBy' => $owner,
                    'slug' => Label::OTHER
                ])
            );
            $accountMovement->setYear($year);
            $accountMovement->setMonth($month);
            $accountMovement->setPrelevedAt(
                $this->dateHelper->createDateFromParts(
                    $movement->getDay(),
                    $month->getSlug(),
                    $year->getValue()
                )
            );
            
            $this->createAccountMovementFixedExpenses($accountMovement);
        }
    }

    public function initializeFixedExpenses(InitializeAccountData $initializeAccountData, User $owner, AccountType $accountType)
    {
        foreach ($initializeAccountData->getFixedExpenses() as $movement) {
            $accountMovement = new AccountMovement();
            $accountMovement->setName($movement->getName());
            $accountMovement->setAmount($movement->getAmount());
            $accountMovement->setAccount(
                $this->entityManager->getRepository(Account::class)->findOneBy([
                    'owner' => $owner,
                    'type' => $accountType,
                ])
            );
            $accountMovement->setLabel(
                $this->entityManager->getRepository(Label::class)->findOneBy([
                    'createdBy' => $owner,
                    'slug' => Label::OTHER
                ])
            );
            $accountMovement->setYear($this->entityManager->getRepository(Year::class)->findOneByValue($initializeAccountData->getYear()));
            $accountMovement->setMonth($this->entityManager->getRepository(Month::class)->findOneBySlug($initializeAccountData->getMonth()));
            $accountMovement->setPrelevedAt(
                $this->dateHelper->createDateFromParts(
                    $movement->getDay(),
                    $initializeAccountData->getMonth(),
                    $initializeAccountData->getYear()
                )
            );
            
            $this->createAccountMovementFixedExpenses($accountMovement);
        }
    }

    public function addExpenses(
        AccountMovementCollectionData $movementCollection,
        Month $month,
        Year $year,
        User $owner,
        AccountType $accountType
    ) {
        foreach ($movementCollection->getCollection() as $movement) {
            $accountMovement = new AccountMovement();
            $accountMovement->setName($movement->getName());
            $accountMovement->setAmount($movement->getAmount());
            $accountMovement->setAccount(
                $this->entityManager->getRepository(Account::class)->findOneBy([
                    'owner' => $owner,
                    'type' => $accountType,
                ])
            );
            $accountMovement->setLabel(
                $this->entityManager->getRepository(AccountMovementType::class)->findOneBy([
                    'createdBy' => $owner,
                    'slug' => Label::OTHER
                ])
            );
            $accountMovement->setYear($year);
            $accountMovement->setMonth($month);
            
            $this->createAccountMovementExpenses($accountMovement);
        }
    }

    public function remove(AccountMovement $accountMovement)
    {
        $this->entityManager->remove($accountMovement);
        $this->entityManager->flush();
    }

    public function update(AccountMovement $accountMovement, AccountMovementData $accountMovementModified)
    {
        if ($accountMovementModified->getName()) {
            $accountMovement->setName($accountMovementModified->getName());
        }

        if ($accountMovementModified->getAmount()) {
            $accountMovement->setAmount($accountMovementModified->getAmount());
        }

        if ($accountMovementModified->getDay()) {
            $accountMovement->setPrelevedAt(
                $this->dateHelper->createDateFromParts(
                    $accountMovementModified->getDay(),
                    $accountMovement->getMonth()->getSlug(),
                    $accountMovement->getYear()->getValue()
                )
            );
        }
        
        $this->flush($accountMovement);
    }

    public function flush(AccountMovement $accountMovement)
    {
        $this->entityManager->persist($accountMovement);
        $this->entityManager->flush();
    }
}
