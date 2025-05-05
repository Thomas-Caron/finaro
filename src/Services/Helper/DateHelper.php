<?php

declare(strict_types=1);

namespace App\Services\Helper;

class DateHelper
{
    public function createDateFromParts(int $day, string $monthSlug, int $year): \DateTimeImmutable
    {
        $months = [
            'janvier' => 1,
            'fevrier' => 2,
            'mars' => 3,
            'avril' => 4,
            'mai' => 5,
            'juin' => 6,
            'juillet' => 7,
            'aout' => 8,
            'septembre' => 9,
            'octobre' => 10,
            'novembre' => 11,
            'decembre' => 12,
        ];

        $month = $months[strtolower($monthSlug)] ?? null;

        if (!$month) {
            throw new \InvalidArgumentException("Mois invalide : $monthSlug");
        }

        return new \DateTimeImmutable(sprintf('%04d-%02d-%02d', $year, $month, $day));
    }
}