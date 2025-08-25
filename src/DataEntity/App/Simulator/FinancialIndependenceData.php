<?php

declare(strict_types=1);

namespace App\DataEntity\App\Simulator;

class FinancialIndependenceData
{
    private ?int $currentHeritage = null;

    private ?int $initialAllocation = null;

    private ?int $monthInvestment = null;

    private ?int $investmentAllocation = null;
    private ?int $savingYears = null;
    private ?int $stockYield = null;
    private ?int $otherYield = null;
    private ?int $withdrawalRate = null;
    private ?int $inflationRate = null;
    private ?float $stockTax = null;
    private ?int $otherTax = null;
    private ?bool $withTax = null;

    public function getCurrentHeritage(): ?int
    {
        return $this->currentHeritage;
    }

    public function setCurrentHeritage(?int $currentHeritage): static
    {
        $this->currentHeritage = $currentHeritage;

        return $this;
    }

    public function getInitialAllocation(): ?int
    {
        return $this->initialAllocation;
    }

    public function setInitialAllocation(?int $initialAllocation): static
    {
        $this->initialAllocation = $initialAllocation;

        return $this;
    }

    public function getMonthInvestment(): ?int
    {
        return $this->monthInvestment;
    }

    public function setMonthInvestment(?int $monthInvestment): static
    {
        $this->monthInvestment = $monthInvestment;

        return $this;
    }

    public function getInvestmentAllocation(): ?int
    {
        return $this->investmentAllocation;
    }

    public function setInvestmentAllocation(?int $investmentAllocation): static
    {
        $this->investmentAllocation = $investmentAllocation;

        return $this;
    }

    public function getSavingYears(): ?int
    {
        return $this->savingYears;
    }

    public function setSavingYears(?int $savingYears): static
    {
        $this->savingYears = $savingYears;

        return $this;
    }

    public function getStockYield(): ?int
    {
        return $this->stockYield;
    }

    public function setStockYield(?int $stockYield): static
    {
        $this->stockYield = $stockYield;

        return $this;
    }

    public function getOtherYield(): ?int
    {
        return $this->otherYield;
    }

    public function setOtherYield(?int $otherYield): static
    {
        $this->otherYield = $otherYield;

        return $this;
    }

    public function getWithdrawalRate(): ?int
    {
        return $this->withdrawalRate;
    }

    public function setWithdrawalRate(?int $withdrawalRate): static
    {
        $this->withdrawalRate = $withdrawalRate;

        return $this;
    }

    public function getInflationRate(): ?int
    {
        return $this->inflationRate;
    }

    public function setInflationRate(?int $inflationRate): static
    {
        $this->inflationRate = $inflationRate;

        return $this;
    }

    public function getStockTax(): ?float
    {
        return $this->stockTax;
    }

    public function setStockTax(?float $stockTax): static
    {
        $this->stockTax = $stockTax;

        return $this;
    }

    public function getOtherTax(): ?int
    {
        return $this->otherTax;
    }

    public function setOtherTax(?int $otherTax): static
    {
        $this->otherTax = $otherTax;

        return $this;
    }

    public function getWithTax(): ?bool
    {
        return $this->withTax;
    }

    public function setWithTax(?bool $withTax): static
    {
        $this->withTax = $withTax;

        return $this;
    }
}