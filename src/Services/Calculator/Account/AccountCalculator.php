<?php

declare(strict_types=1);

namespace App\Services\Calculator\Account;

use App\Entity\Account\Account;
use App\Entity\Account\Movement\Enum\TransactionDirection;
use App\Entity\Date\Month;
use App\Entity\Date\Year;
use App\Repository\Account\Movement\AccountMovementRepository;
use App\Repository\Date\MonthRepository;

class AccountCalculator
{
    public function __construct(
        private readonly AccountMovementRepository $accountMovementRepository,
        private readonly MonthRepository $monthRepository
    ) {
    }

    public function calculateRemaining(Account $account, Month $month, Year $year): array
    {
        $remainingPrevious = $this->accountMovementRepository->getRemainingPrevious($account, $month, $year);
        $incomes = $this->accountMovementRepository->getIncomes($account, $month, $year);
        $fixedExpenses = $this->accountMovementRepository->getFixedExpenses($account, $month, $year);
        $expenses = $this->accountMovementRepository->getExpenses($account, $month, $year);

        $applyDirection = fn($item) => $item->getTransactionDirection() === TransactionDirection::CREDIT
            ? $item->getAmount()
            : -$item->getAmount();

        $totalIncomes = array_sum(array_map($applyDirection, [...$remainingPrevious, ...$incomes]));
        $totalExpenses = array_sum(array_map($applyDirection, [...$fixedExpenses, ...$expenses]));

        $totalRemaining = $totalIncomes + $totalExpenses;

        $totalRemainingPercent = $totalIncomes > 0
            ? max(($totalRemaining / $totalIncomes) * 100, 0)
            : 0;

        $totalSpentPercent = 100 - $totalRemainingPercent;

        return [
            'totalIncomes' => $totalIncomes,
            'totalExpenses' => $totalExpenses,
            'totalRemaining' => $totalRemaining,
            'totalRemainingPercent' => $totalRemainingPercent,
            'totalSpentPercent' => $totalSpentPercent,
        ];
    }

    public function calculateRemainingByYear(Account $account, Year $year): array
    {
        $movements = $this->accountMovementRepository->findBy([
            'account' => $account,
            'year' => $year,
        ]);

        $monthlyData = [];

        foreach ($movements as $movement) {
            $month = $movement->getMonth();
            $monthSlug = $month->getName();
            $direction = $movement->getTransactionDirection();
            $amount = $movement->getAmount();

            if (!isset($monthlyData[$monthSlug])) {
                $monthlyData[$monthSlug] = [
                    'month' => $month->getName(),
                    'remaining' => 0,
                ];
            }

            if ($direction === TransactionDirection::CREDIT) {
                $monthlyData[$monthSlug]['remaining'] += $amount;
            } else {
                $monthlyData[$monthSlug]['remaining'] -= $amount;
            }
        }

        uasort($monthlyData, function ($a, $b) use ($year) {
            $months = $this->monthRepository->findBy([], ['position' => 'ASC']);
            $map = array_flip(array_map(fn($month) => $month->getName(), $months));
            return $map[$a['month']] <=> $map[$b['month']];
        });

        $labels = [];
        $series = [];

        foreach ($monthlyData as $data) {
            $labels[] = $data['month'];
            $series[] = round($data['remaining'], 2);
        }

        return [
            'labels' => $labels,
            'series' => $series,
        ];
    }

    public function calculateByLabel(Account $account, Month $month, Year $year): array
    {
        $fixedExpenses = $this->accountMovementRepository->getFixedExpenses($account, $month, $year);
        $expenses = $this->accountMovementRepository->getExpenses($account, $month, $year);

        $allMovements = [...$fixedExpenses, ...$expenses];

        $totalByLabel = [];

        foreach ($allMovements as $movement) {
            $amount = $movement->getAmount();
            $direction = $movement->getTransactionDirection();

            if ($amount === null || $amount === 0) {
                continue;
            }

            $signedAmount = $direction === TransactionDirection::CREDIT ? -$amount : $amount;

            $label = $movement->getLabel();
            $labelName = $label?->getName() ?? 'Non catégorisé';
            $labelColor = $label?->getColor() ?? '#A3A3A3';

            if (!isset($totalByLabel[$labelName])) {
                $totalByLabel[$labelName] = [
                    'label' => $labelName,
                    'color' => $labelColor,
                    'total' => 0,
                ];
            }

            $totalByLabel[$labelName]['total'] += $signedAmount;
        }

        $filtered = array_filter($totalByLabel, fn($data) => $data['total'] > 0);

        $series = [];
        $labels = [];
        $colors = [];

        foreach ($filtered as $data) {
            $series[] = round($data['total'], 2);
            $labels[] = $data['label'];
            $colors[] = $data['color'];
        }

        return [
            'series' => $series,
            'labels' => $labels,
            'colors' => $colors,
        ];
    }
}
