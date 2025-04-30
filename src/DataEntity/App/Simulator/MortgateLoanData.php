<?php

declare(strict_types=1);

namespace App\DataEntity\App\Simulator;

class MortgateLoanData
{
    private ?float $amountBorrowed = null;

    private ?int $repaymentPeriod = null;

    private ?float $interestRate = null;
    
    private ?float $insuranceRate = null;

    public function getAmountBorrowed(): ?float
    {
        return $this->amountBorrowed;
    }

    public function setAmountBorrowed(?float $amountBorrowed): static
    {
        $this->amountBorrowed = $amountBorrowed;

        return $this;
    }

    public function getRepaymentPeriod(): ?int
    {
        return $this->repaymentPeriod;
    }

    public function setRepaymentPeriod(?int $repaymentPeriod): static
    {
        $this->repaymentPeriod = $repaymentPeriod;

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

    public function getInsuranceRate(): ?float
    {
        return $this->insuranceRate;
    }

    public function setInsuranceRate(?float $insuranceRate): static
    {
        $this->insuranceRate = $insuranceRate;

        return $this;
    }
}
