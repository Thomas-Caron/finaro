<?php

declare(strict_types=1);

namespace Finaro\Fixtures\Mandatory\Account;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Finaro\Entity\Account\AccountType;

class AccountTypeFixtures extends Fixture
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