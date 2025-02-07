<?php

declare(strict_types=1);

namespace Finaro\Fixtures\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Finaro\Entity\User\User;
use Finaro\Services\Manager\User\UserManager;

class UserFixtures extends Fixture
{
    public function __construct(private readonly UserManager $userManager)
    {
    }

    public static function getGroups(): array
    {
        return ['test'];
    }

    public function load(ObjectManager $manager): void
{
        $user = new User();
        $user->setFirstname('John');
        $user->setLastname('Dow');
        $user->setEmail('test@test.com');
        $user->setPassword('password');

        $user = $this->userManager->createUser($user);
    }
}