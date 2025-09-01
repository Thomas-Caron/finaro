<?php

declare(strict_types=1);

namespace App\Services\Manager\Label;

use App\DataEntity\App\Label\LabelCollectionData;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Label\Label;
use App\Entity\User\User;
use App\Repository\Account\Movement\AccountMovementRepository;
use App\Repository\Label\LabelRepository;

class LabelManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LabelRepository $labelRepository,
        private readonly AccountMovementRepository $accountMovementRepository
    ) {
    }

    public function createLabel(Label $label): Label
    {
        $this->flush($label);

        return $label;
    }

    public function createOtherLabel(User $user): Label
    {
        $label = new Label();
        $label->setName(ucfirst(strtolower(Label::OTHER)));
        $label->setColor('#A3A3A3');
        $label->setIcon('Package');
        $label->setCreatedBy($user);

        $label = $this->createLabel($label);

        return $label;
    }

    public function addLabels(
        LabelCollectionData $labelCollection,
        User $user
    ) {
        foreach ($labelCollection->getCollection() as $label) {
            $newLabel = new Label();
            $newLabel->setName($label->getName());
            $newLabel->setColor($label->getColor());
            $newLabel->setIcon($label->getIcon());
            $newLabel->setCreatedBy($user);
            
            $this->createLabel($newLabel);
        }
    }

    public function update(Label $label, Label $labelModified)
    {
        if (null !== $labelModified->getName()) {
            $label->setName($labelModified->getName());
        }

        if (null !== $labelModified->getColor()) {
            $label->setColor($labelModified->getColor());
        }

        if (null !== $labelModified->getIcon()) {
            $label->setIcon($labelModified->getIcon());
        }
        
        $this->flush($label);
    }

    public function remove(Label $label)
    {
        if ($label->getSlug() === 'autre') {
            throw new \LogicException('La caégorie "Autre" ne peut pas être supprimée.');
        }

        $user = $label->getCreatedBy();

        $defaultLabel = $this->labelRepository->findOneBy([
            'createdBy' => $user,
            'slug' => 'autre'
        ]);

        if (!$defaultLabel) {
            throw new \LogicException('La catégorie par défaut "Autre" est introuvable.');
        }

        $accountMovements = $this->accountMovementRepository->findBy(['label' => $label]);

        foreach ($accountMovements as $movement) {
            $movement->setLabel($defaultLabel);
        }

        $this->entityManager->remove($label);
        $this->entityManager->flush();
    }

    public function flush(Label $label)
    {
        $this->entityManager->persist($label);
        $this->entityManager->flush();
    }
}
