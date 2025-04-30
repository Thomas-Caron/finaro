<?php

declare(strict_types=1);

namespace App\Entity\Account\Movement;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\Account\Movement\AccountMovementTypeRepository;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: AccountMovementTypeRepository::class)]
#[ORM\Table(name: '`account_movement_type`')]
class AccountMovementType
{
    public const REMAINING_PREVIOUS = 'restant-precedent';
    public const INCOMES = 'revenus';
    public const FIXED_EXPENSES = 'depenses-fixes';
    public const EXPENSES = 'depenses';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Gedmo\Slug(fields: ["name"])]
    private ?string $slug = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, AccountMovement>
     */
    #[ORM\OneToMany(targetEntity: AccountMovement::class, mappedBy: 'movementType', orphanRemoval: true)]
    private Collection $accountMovements;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

     /**
     * @return Collection<int, AccountMovement>
     */
    public function getAccountMovements(): Collection
    {
        return $this->accountMovements;
    }

    public function addAccountMovement(AccountMovement $accountMovement): static
    {
        if (!$this->accountMovements->contains($accountMovement)) {
            $this->accountMovements->add($accountMovement);
            $accountMovement->setMovementType($this);
        }

        return $this;
    }

    public function removeAccountMovement(AccountMovement $accountMovement): static
    {
        if ($this->accountMovements->removeElement($accountMovement)) {
            // set the owning side to null (unless already changed)
            if ($accountMovement->getMovementType() === $this) {
                $accountMovement->setMovementType(null);
            }
        }

        return $this;
    }
}
