<?php

declare(strict_types=1);

namespace App\Entity\Account\Movement;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use App\Entity\Account\Account;
use App\Entity\Date\Month;
use App\Entity\Date\Year;
use App\Entity\Label\Label;
use App\Repository\Account\Movement\AccountMovementRepository;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: AccountMovementRepository::class)]
#[ORM\Table(name: '`account_movement`')]
class AccountMovement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'accountMovements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $account = null;

    #[ORM\ManyToOne(inversedBy: 'accountMovements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Label $label = null;

    #[ORM\ManyToOne(inversedBy: 'accountMovements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?AccountMovementType $movementType = null;

    #[ORM\ManyToOne(inversedBy: 'accountMovements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Year $year = null;

    #[ORM\ManyToOne(inversedBy: 'accountMovements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Month $month = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeInterface $updatedAt = null;

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

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): static
    {
        $this->account = $account;

        return $this;
    }

    public function getLabel(): ?Label
    {
        return $this->label;
    }

    public function setLabel(?Label $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getMovementType(): ?AccountMovementType
    {
        return $this->movementType;
    }

    public function setMovementType(?AccountMovementType $movementType): static
    {
        $this->movementType = $movementType;

        return $this;
    }

    public function getYear(): ?Year
    {
        return $this->year;
    }

    public function setYear(?Year $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getMonth(): ?Month
    {
        return $this->month;
    }

    public function setMonth(?Month $month): static
    {
        $this->month = $month;

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
