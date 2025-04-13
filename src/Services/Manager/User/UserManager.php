<?php

declare(strict_types=1);

namespace App\Services\Manager\User;

use App\Entity\User\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function createUser(User $user)
    {
        $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);

        $this->flush($user);
    }

    public function flush(User $user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
