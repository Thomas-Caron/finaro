<?php

declare(strict_types=1);

namespace App\Services\Manager\Label;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Label\Label;
use App\Entity\User\User;

class LabelManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function createLabel(User $owner, string $name, string $color): Label
    {
        $label = new Label();
        $label->setName($name);
        $label->setColor($color);
        $label->setCreatedBy($owner);

        $this->flush($label);

        return $label;
    }

    public function createOtherLabel(User $user): Label
    {
        $label = $this->createLabel($user, strtoupper(Label::OTHER), 'sky');

        return $label;
    }

    public function flush(Label $label)
    {
        $this->entityManager->persist($label);
        $this->entityManager->flush();
    }
}
