<?php

declare(strict_types=1);

namespace Finaro\Services\Calculator\Simulator;

class MortgateLoanCalculator
{
    public function calculate(
        float $amountBorrowed,
        float $repaymentPeriod,
        float $interestRate,
        float $insuranceRate
    ): array {
        $months = $repaymentPeriod * 12;
        $monthlyInterestRate = ($interestRate / 100) / 12;

        $monthlyPayment = ($monthlyInterestRate > 0)
            ? $amountBorrowed * ($monthlyInterestRate / (1 - pow(1 + $monthlyInterestRate, -$months)))
            : $amountBorrowed / $months;

        $monthlyInsurance = ($amountBorrowed * ($insuranceRate / 100)) / 12;
        $totalInsurance = $monthlyInsurance * $months;

        $balance = $amountBorrowed;
        $totalInterest = 0;
        $chartData = [];

        for ($month = 1; $month <= $months; $month++) {
            $interestPayment = $balance * $monthlyInterestRate;
            $capitalPayment = $monthlyPayment - $interestPayment;
            $balance -= $capitalPayment;
            $totalInterest += $interestPayment;

            $year = (int) ceil($month / 12);

            if ($month % 12 === 1) {

                // Ajout des données pour le graphique
                $chartData[] = [
                    'year' => $year,
                    'capital' => round($capitalPayment, 0),
                    'interest' => round($interestPayment, 0),
                    'insurance' => round($monthlyInsurance, 0),
                ];
            }
        }

        $totalCreditCost = $totalInterest + $amountBorrowed;
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
