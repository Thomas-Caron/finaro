<?php

declare(strict_types=1);

namespace App\Entity\Date;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Account\Movement\AccountMovement;
use App\Repository\Date\MonthRepository;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: MonthRepository::class)]
#[ORM\Table(name: '`month`')]
class Month
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Gedmo\Slug(fields: ["name"])]
    private ?string $slug = null;

    #[ORM\Column]
    private ?int $position = null;

    /**
     * @var Collection<int, AccountMovement>
     */
    #[ORM\OneToMany(targetEntity: AccountMovement::class, mappedBy: 'month', orphanRemoval: true)]
    private Collection $accountMovements;

    public function __construct()
    {
        $this->accountMovements = new ArrayCollection();
    }

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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

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
            $accountMovement->setMonth($this);
        }

        return $this;
    }

    public function removeAccountMovement(AccountMovement $accountMovement): static
    {
        if ($this->accountMovements->removeElement($accountMovement)) {
            // set the owning side to null (unless already changed)
            if ($accountMovement->getMonth() === $this) {
                $accountMovement->setMonth(null);
            }
        }

        return $this;
    }
}
