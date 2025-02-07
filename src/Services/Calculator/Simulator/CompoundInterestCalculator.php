<?php

declare(strict_types=1);

namespace Finaro\Services\Calculator\Simulator;

class CompoundInterestCalculator
{
    public function calculate(
        float $initialCapital,
        float $monthlySavings,
        int $investmentHorizon,
        float $interestRate,
        int $interestPaymentInterval
    ): array {
        $interestRate = $interestRate / 100; // Convertir en décimal (ex: 5% → 0.05)
        $periodsPerYear = 12 / $interestPaymentInterval; // Nombre de capitalisations par an
        $interestPerPeriod = $interestRate / $periodsPerYear; // Taux d'intérêt appliqué par période
        $savingsPerPeriod = $monthlySavings * $interestPaymentInterval; // Versement ajusté selon l'intervalle

        $capital = $initialCapital;
        $totalSavings = $initialCapital;
        $cumulativeInterest = 0;
        $results = [];

        for ($year = 1; $year <= $investmentHorizon; $year++) {
            $yearlyInterest = 0;

            for ($period = 1; $period <= $periodsPerYear; $period++) {
                $interest = $capital * $interestPerPeriod; // Intérêt pour cette période
                $capital += $interest + $savingsPerPeriod; // Ajout des intérêts et de l'épargne
                $totalSavings += $savingsPerPeriod; // Mise à jour du total des versements

                $yearlyInterest += $interest;
            }

            $cumulativeInterest += $yearlyInterest;

            $results[] = [
                'year' => $year,
                'interest' => round($cumulativeInterest, 0), // Intérêts générés sur l'année
                'saving' => round($totalSavings, 0), // Total des versements (capital initial + versements)
                'capital' => round($capital, 0), // Capital total (épargne + intérêts)
            ];
        }

        return $results;
    }
}
