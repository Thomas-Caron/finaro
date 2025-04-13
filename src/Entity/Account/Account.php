<?php

declare(strict_types=1);

namespace App\Entity\Account;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use App\Entity\Account\AccountType;
use App\Entity\Account\Movement\AccountMovement;
use App\Entity\User\User;
use App\Repository\Account\AccountRepository;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
#[ORM\Table(name: '`account`')]
class Account
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'accounts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?AccountType $type = null;

    #[ORM\ManyToOne(inversedBy: 'ownedAccounts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\ManyToOne(inversedBy: 'collaboratedAccounts')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $collaborator = null;

    /**
     * @var Collection<int, AccountMovement>
     */
    #[ORM\OneToMany(targetEntity: AccountMovement::class, mappedBy: 'account', orphanRemoval: true)]
    private Collection $accountMovements;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->accountMovements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?AccountType
    {
        return $this->type;
    }

    public function setType(?AccountType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getCollaborator(): ?User
    {
        return $this->collaborator;
    }

    public function setCollaborator(?User $collaborator): static
    {
        $this->collaborator = $collaborator;

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
            $accountMovement->setAccount($this);
        }

        return $this;
    }

    public function removeAccountMovement(AccountMovement $accountMovement): static
    {
        if ($this->accountMovements->removeElement($accountMovement)) {
            // set the owning side to null (unless already changed)
            if ($accountMovement->getAccount() === $this) {
                $accountMovement->setAccount(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }
}
