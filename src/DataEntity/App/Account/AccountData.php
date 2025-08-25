<?php

declare(strict_types=1);

namespace App\DataEntity\App\Account;

use App\DataEntity\App\Account\Movement\AccountMovementData;

class AccountData
{
    private array $remainingPrevious = [];

    private array $incomes = [];

    private array $fixedExpenses = [];

    private ?string $month = null;

    private ?int $year = null;

    public function getRemainingPrevious(): array
    {
        return $this->remainingPrevious;
    }

    public function setRemainingPrevious(array $remainingPrevious): static
    {
        $this->remainingPrevious = $remainingPrevious;

        return $this;
    }

    public function addRemainingPrevious(AccountMovementData $remainingPrevious): static
    {
        $this->remainingPrevious[] = $remainingPrevious;
        return $this;
    }

    public function removeRemainingPrevious(AccountMovementData $remainingPrevious): static
    {
        $this->remainingPrevious = array_filter($this->remainingPrevious, fn($item) => $item !== $remainingPrevious);

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
