<?php

declare(strict_types=1);

namespace App\Services\Calculator\Account;

use App\Entity\Account\Account;
use App\Entity\Date\Month;
use App\Entity\Date\Year;
use App\Repository\Account\Movement\AccountMovementRepository;

class AccountCalculator
{
    public function __construct(
        private readonly AccountMovementRepository $accountMovementRepository,
    ) {
    }

    public function calculate(Account $account, Month $month, Year $year): array
    {
        $remainingPrevious = $this->accountMovementRepository->getRemainingPrevious($account, $month, $year);
        $incomes = $this->accountMovementRepository->getIncomes($account, $month, $year);
        $fixedExpenses = $this->accountMovementRepository->getFixedExpenses($account, $month, $year);
        $expenses = $this->accountMovementRepository->getExpenses($account, $month, $year);

        $totalIncomes = array_sum(array_map(fn($item) => $item->getAmount(), $remainingPrevious)) 
                  + array_sum(array_map(fn($item) => $item->getAmount(), $incomes));

        $totalExpenses = array_sum(array_map(fn($item) => $item->getAmount(), $fixedExpenses)) 
                    + array_sum(array_map(fn($item) => $item->getAmount(), $expenses));

        $totalRemaining = $totalIncomes - $totalExpenses;

        if ($totalIncomes > 0) {
            $totalRemainingPercent = max(($totalRemaining / $totalIncomes) * 100, 0);
        } else {
            $totalRemainingPercent = 0;
        }
    
        $totalSpentPercent = 100 - $totalRemainingPercent;


        return [
            'totalIncomes' => $totalIncomes,
            'totalExpenses' => $totalExpenses,
            'totalRemaining' => $totalRemaining,
            'totalRemainingPercent' => $totalRemainingPercent,
            'totalSpentPercent' => $totalSpentPercent
        ];
    }
}
