<?php

declare(strict_types=1);

namespace App\Entity\Account\Movement\Enum;

enum TransactionDirection: string
{
    case CREDIT = 'credit';
    case DEBIT = 'debit';
}