<?php

declare(strict_types=1);

namespace App\DataEntity\App\Account\Movement;

use App\Entity\Account\Movement\Enum\TransactionDirection;
use App\Entity\Label\Label;

class AccountMovementData
{
    private ?int $id = null;

    private ?Label $label = null;

    private ?string $name = null;

    private ?float $amount = null;

    private ?TransactionDirection $transactionDirection = null;
    
    private ?bool $isCharged = null;

    private ?int $day = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getTransactionDirection(): ?TransactionDirection
    {
        return $this->transactionDirection;
    }

    public function setTransactionDirection(?TransactionDirection $transactionDirection): static
    {
        $this->transactionDirection = $transactionDirection;

        return $this;
    }

    public function isCharged(): ?bool
    {
        return $this->isCharged;
    }
    
    public function setIsCharged(?bool $isCharged): static
    {
        $this->isCharged = $isCharged;

        return $this;
    }

    public function getDay(): ?int
    {
        return $this->day;
    }

    public function setDay(?int $day): static
    {
        $this->day = $day;

        return $this;
    }
}
