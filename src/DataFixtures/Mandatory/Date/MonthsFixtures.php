<?php

declare(strict_types=1);

namespace App\DataFixtures\Mandatory\Date;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Date\Month;

class MonthsFixtures extends Fixture
{
    public static function getGroups(): array
    {
        return ['mandatory'];
    }
    
    public function load(ObjectManager $manager): void
    {
        $months = [
            ['name' => 'Janvier', 'position' => 1],
            ['name' => 'Février', 'position' => 2],
            ['name' => 'Mars', 'position' => 3],
            ['name' => 'Avril', 'position' => 4],
            ['name' => 'Mai', 'position' => 5],
            ['name' => 'Juin', 'position' => 6],
            ['name' => 'Juillet', 'position' => 7],
            ['name' => 'Août', 'position' => 8],
            ['name' => 'Septembre', 'position' => 9],
            ['name' => 'Octobre', 'position' => 10],
            ['name' => 'Novembre', 'position' => 11],
            ['name' => 'Décembre', 'position' => 12],
        ];

        foreach ($months as $monthData) {
            $month = new Month();
            $month->setName($monthData['name']);
            $month->setPosition($monthData['position']);
 
            $manager->persist($month);
        }

        $manager->flush();
    }
}