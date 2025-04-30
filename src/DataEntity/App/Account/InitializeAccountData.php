<?php

declare(strict_types=1);

namespace App\DataEntity\App\Account;

use App\DataEntity\App\Account\Movement\AccountMovementData;
use Symfony\Component\Validator\Constraints as Assert;

class InitializeAccountData
{
    #[Assert\NotBlank(message: 'Veuillez saisir une valeur.')]
    private ?float $startingBalance = null;

    private array $incomes = [];

    private array $fixedExpenses = [];

    private ?string $month = null;

    private ?int $year = null;

    public function getStartingBalance(): ?float
    {
        return $this->startingBalance;
    }

    public function setStartingBalance(?float $startingBalance): static
    {
        $this->startingBalance = $startingBalance;

        return $this;
    }

    public function getIncomes(): array
    {
        return $this->incomes;
    }

    public function setIncomes(array $incomes): static
    {
        $this->incomes = $incomes;
        return $this;
    }

    public function addIncome(AccountMovementData $income): static
    {
        $this->incomes[] = $income;
        return $this;
    }

    public function removeIncome(AccountMovementData $income): static
    {
        $this->incomes = array_filter($this->incomes, fn($item) => $item !== $income);

        return $this;
    }

    public function getFixedExpenses(): array
    {
        return $this->fixedExpenses;
    }

    public function setFixedExpenses(array $fixedExpenses): static
    {
        $this->fixedExpenses = $fixedExpenses;
        return $this;
    }

    public function addFixedExpense(AccountMovementData $fixedExpense): static
    {
        $this->fixedExpenses[] = $fixedExpense;
        return $this;
    }

    public function removeFixedExpense(AccountMovementData $fixedExpense): static
    {
        $this->fixedExpenses = array_filter($this->fixedExpenses, fn($item) => $item !== $fixedExpense);
        
        return $this;
    }

    public function getMonth(): ?string
    {
        return $this->month;
    }

    public function setMonth(?string $month): static
    {
        $this->month = $month;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): static
    {
        $this->year = $year;

        return $this;
    }
}
