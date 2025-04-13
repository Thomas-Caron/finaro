<?php

declare(strict_types=1);

namespace App\DataFixtures\Mandatory\Account;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Account\Movement\AccountMovementType;

class AccountMovementTypeFixtures extends Fixture
{
    public static function getGroups(): array
    {
        return ['mandatory'];
    }
    
    public function load(ObjectManager $manager): void
    {
        $types = [
            'Restant précédent',
            'Revenus',
            'Dépenses fixes',
            'Dépenses',
        ];

        foreach ($types as $typeData) {
            $movementType = new AccountMovementType();
            $movementType->setName($typeData);
            $manager->persist($movementType);
        }

        $manager->flush();
    }
}