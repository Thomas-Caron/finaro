<?php

declare(strict_types=1);

namespace App\Entity\Date;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Account\Movement\AccountMovement;
use App\Repository\Date\YearRepository;

#[ORM\Entity(repositoryClass: YearRepository::class)]
#[ORM\Table(name: '`year`')]
class Year
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $value = null;

    /**
     * @var Collection<int, AccountMovement>
     */
    #[ORM\OneToMany(targetEntity: AccountMovement::class, mappedBy: 'year', orphanRemoval: true)]
    private Collection $accountMovements;

    public function __construct()
    {
        $this->accountMovements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;

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
            $accountMovement->setYear($this);
        }

        return $this;
    }

    public function removeAccountMovement(AccountMovement $accountMovement): static
    {
        if ($this->accountMovements->removeElement($accountMovement)) {
            // set the owning side to null (unless already changed)
            if ($accountMovement->getYear() === $this) {
                $accountMovement->setYear(null);
            }
        }

        return $this;
    }
}
