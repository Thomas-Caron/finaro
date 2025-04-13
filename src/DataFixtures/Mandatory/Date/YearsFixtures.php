<?php

declare(strict_types=1);

namespace App\DataFixtures\Mandatory\Date;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Date\Year;

class YearsFixtures extends Fixture
{
    public static function getGroups(): array
    {
        return ['mandatory'];
    }
    
    public function load(ObjectManager $manager): void
    {
        $currentYear = (int) date('Y');

        for ($i = 0; $i < 10; $i++) {
            $year = new Year();
            $year->setValue($currentYear + $i);

            $manager->persist($year);
        }
        $manager->flush();
    }
}