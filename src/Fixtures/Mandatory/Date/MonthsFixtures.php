<?php

declare(strict_types=1);

namespace Finaro\Fixtures\Mandatory\Date;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Finaro\Entity\Date\Month;

class MonthsFixtures extends Fixture
{
    public static function getGroups(): array
    {
        return ['mandatory'];
    }
    
    public function load(ObjectManager $manager): void
    {
        $months = [
            ['name' => 'Janvier', 'index' => 1],
            ['name' => 'Février', 'index' => 2],
            ['name' => 'Mars', 'index' => 3],
            ['name' => 'Avril', 'index' => 4],
            ['name' => 'Mai', 'index' => 5],
            ['name' => 'Juin', 'index' => 6],
            ['name' => 'Juillet', 'index' => 7],
            ['name' => 'Août', 'index' => 8],
            ['name' => 'Septembre', 'index' => 9],
            ['name' => 'Octobre', 'index' => 10],
            ['name' => 'Novembre', 'index' => 11],
            ['name' => 'Décembre', 'index' => 12],
        ];

        foreach ($months as $monthData) {
            $month = new Month();
            $month->setName($monthData['name']);
            $month->setIndex($monthData['index']);
 
            $manager->persist($month);
        }

        $manager->flush();
    }
}