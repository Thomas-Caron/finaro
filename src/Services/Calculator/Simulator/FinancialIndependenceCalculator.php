<?php

declare(strict_types=1);

namespace App\Services\Calculator\Simulator;

use App\DataEntity\App\Simulator\FinancialIndependenceData;

class FinancialIndependenceCalculator
{
    public function calculate(FinancialIndependenceData $financialIndependence): array
    {
        $heritage = $financialIndependence->getCurrentHeritage();
        $allocationOther = $financialIndependence->getInitialAllocation() / 100;
        $investmentOther = $financialIndependence->getInvestmentAllocation() / 100;
        $monthInvestment = $financialIndependence->getMonthInvestment();
        $years = $financialIndependence->getSavingYears();
        $stockYield = $financialIndependence->getStockYield() / 100;
        $otherYield = $financialIndependence->getOtherYield() / 100;
        $withdrawalRate = $financialIndependence->getWithdrawalRate() / 100;
        $inflationRate = $financialIndependence->getInflationRate() / 100;
        $stockTax = $financialIndependence->getStockTax() / 100;
        $otherTax = $financialIndependence->getOtherTax() / 100;
        $withTax = $financialIndependence->getWithTax();

        $initialHeritage = $heritage;
        $totalDeposits = 0;

        $stock = $heritage * (1 - $allocationOther); // Bourse
        $other = $heritage * $allocationOther;

        $results = [];

        for ($year = 1; $year <= $years; $year++) {
            $annualInvestment = $monthInvestment * 12;
            $totalDeposits += $annualInvestment;

            // Répartition des investissements annuels
            $stock += $annualInvestment * (1 - $investmentOther);
            $other += $annualInvestment * $investmentOther;

            // Croissance brute
            $stockGrowth = $stock * $stockYield;
            $otherGrowth = $other * $otherYield;

            // Application des taxes le cas échéant
            if ($withTax) {
                $stockGrowth *= (1 - $stockTax);
                $otherGrowth *= (1 - $otherTax);
            }

            // Mise à jour des montants
            $stock += $stockGrowth;
            $other += $otherGrowth;

            // Ajustement à l’inflation (valeur constante)
            $stock /= (1 + $inflationRate);
            $other /= (1 + $inflationRate);

            // Total brut
            $total = $stock + $other;

            // Intérêts cumulés
            $interest = $total - $initialHeritage - $totalDeposits;

            $interest = $total - $initialHeritage - $totalDeposits;

            $results[] = [
                'year' => $year,
                'interest' => round($interest),
                'deposit' => $totalDeposits,
                'initialHeritage' => $initialHeritage,
                'total' => round($initialHeritage + $totalDeposits + $interest),
            ];
        }

        $last = end($results);
        $safeWithdrawal = round(($last['total'] ?? 0) * $withdrawalRate / 12);

        return [
            'years' => array_column($results, 'year'),
            'interests' => array_column($results, 'interest'),
            'deposits' => array_column($results, 'deposit'),
            'initialHeritage' => array_column($results, 'initialHeritage'),
            'total' => array_column($results, 'total'),
            'safeWithdrawal' => $safeWithdrawal,
        ];
    }
}