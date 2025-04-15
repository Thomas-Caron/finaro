<?php

declare(strict_types=1);

namespace App\Services\Calculator\Simulator;

use App\Entity\Simulator\MortgateLoanData;

class MortgateLoanCalculator
{
    public function calculate(MortgateLoanData $mortgateLoan): array
    {
        $months = $mortgateLoan->getRepaymentPeriod() * 12;
        $monthlyInterestRate = ($mortgateLoan->getInterestRate() / 100) / 12;

        $monthlyPayment = ($monthlyInterestRate > 0)
            ? $mortgateLoan->getAmountBorrowed() * ($monthlyInterestRate / (1 - pow(1 + $monthlyInterestRate, -$months)))
            : $mortgateLoan->getAmountBorrowed() / $months;

        $monthlyInsurance = ($mortgateLoan->getAmountBorrowed() * ($mortgateLoan->getInsuranceRate() / 100)) / 12;
        $totalInsurance = $monthlyInsurance * $months;

        $balance = $mortgateLoan->getAmountBorrowed();
        $totalInterest = 0;
        $chartData = [];

        for ($month = 1; $month <= $months; $month++) {
            $interestPayment = $balance * $monthlyInterestRate;
            $capitalPayment = $monthlyPayment - $interestPayment;
            $balance -= $capitalPayment;
            $totalInterest += $interestPayment;

            $year = (int) ceil($month / 12);

            if ($month % 12 === 1) {
                $chartData[] = [
                    'year' => $year,
                    'capital' => round($capitalPayment, 0),
                    'interest' => round($interestPayment, 0),
                    'insurance' => round($monthlyInsurance, 0),
                ];
            }
        }

        $totalCreditCost = $totalInterest + $mortgateLoan->getAmountBorrowed();
        $totalAmount = $totalCreditCost + $totalInsurance;

        return [
            'monthlyPayment' => round($monthlyPayment + $monthlyInsurance, 0),
            'monthlyInsurance' => round($monthlyInsurance, 0),
            'totalInsurance' => round($totalInsurance, 0),
            'totalCreditCost' => round($totalInterest + $totalInsurance, 0),
            'totalAmount' => round($totalAmount, 0),
            'chartData' => $chartData,
        ];
    }
}
