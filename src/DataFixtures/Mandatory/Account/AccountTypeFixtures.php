<?php

declare(strict_types=1);

namespace App\DataFixtures\Mandatory\Account;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Account\AccountType;

class AccountTypeFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['mandatory'];
    }
    
    public function load(ObjectManager $manager): void
    {
        $types = [
            'Courant',
            'Commun',
        ];

        foreach ($types as $typeData) {
            $accountType = new AccountType();
            $accountType->setName($typeData);
            $manager->persist($accountType);
        }

        $manager->flush();
    }
}