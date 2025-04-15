<?php

declare(strict_types=1);

namespace App\Entity\Simulator;

class CompoundInterestData
{
    private ?float $initialCapital = null;

    private ?float $monthlySavings = null;

    private ?int $investmentHorizon = null;

    private ?float $interestRate = null;
    
    private ?int $interestPaymentInterval = null;

    public function getInitialCapital(): ?float
    {
        return $this->initialCapital;
    }

    public function setInitialCapital(?float $initialCapital): static
    {
        $this->initialCapital = $initialCapital;

        return $this;
    }

    public function getMonthlySavings(): ?float
    {
        return $this->monthlySavings;
    }

    public function setMonthlySavings(?float $monthlySavings): static
    {
        $this->monthlySavings = $monthlySavings;

        return $this;
    }

    public function getInvestmentHorizon(): ?int
    {
        return $this->investmentHorizon;
    }

    public function setInvestmentHorizon(?int $investmentHorizon): static
    {
        $this->investmentHorizon = $investmentHorizon;

        return $this;
    }

    public function getInterestRate(): ?float
    {
        return $this->interestRate;
    }

    public function setInterestRate(?float $interestRate): static
    {
        $this->interestRate = $interestRate;

        return $this;
    }

    public function getInterestPaymentInterval(): ?int
    {
        return $this->interestPaymentInterval;
    }

    public function setInterestPaymentInterval(?int $interestPaymentInterval): static
    {
        $this->interestPaymentInterval = $interestPaymentInterval;

        return $this;
    }
}
