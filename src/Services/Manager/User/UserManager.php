<?php

declare(strict_types=1);

namespace Finaro\Services\Manager\User;

use Finaro\Entity\User\User;
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
