<?php

declare(strict_types=1);

namespace App\Services\Calculator\Simulator;

use App\DataEntity\App\Simulator\CompoundInterestData;

class CompoundInterestCalculator
{
    public function calculate(CompoundInterestData $compoundInterest): array
    {
        $interestRate = $compoundInterest->getInterestRate() / 100;
        $periodsPerYear = 12 / $compoundInterest->getInterestPaymentInterval();
        $interestPerPeriod = $interestRate / $periodsPerYear;
        $savingsPerPeriod = $compoundInterest->getMonthlySavings() * $compoundInterest->getInterestPaymentInterval();

        $capital = $compoundInterest->getInitialCapital();
        $totalSavings = $compoundInterest->getInitialCapital();
        $cumulativeInterest = 0;
        $results = [];

        for ($year = 1; $year <= $compoundInterest->getInvestmentHorizon(); $year++) {
            $yearlyInterest = 0;

            for ($period = 1; $period <= $periodsPerYear; $period++) {
                $interest = $capital * $interestPerPeriod;
                $capital += $interest + $savingsPerPeriod;
                $totalSavings += $savingsPerPeriod;

                $yearlyInterest += $interest;
            }

            $cumulativeInterest += $yearlyInterest;

            $results[] = [
                'year' => $year,
                'interest' => round($cumulativeInterest, 0),
                'saving' => round($totalSavings, 0),
                'capital' => round($capital, 0),
            ];
        }

        return [
            'years' => array_map(fn($item) => $item['year'], $results),
            'capital' => array_map(fn($item) => $item['capital'], $results),
            'saving' => array_map(fn($item) => $item['saving'], $results),
            'interest' => array_map(fn($item) => $item['interest'], $results),
        ];
    }
}
