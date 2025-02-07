<?php

declare(strict_types=1);

namespace Finaro\Twig\Filter;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ConvertFilter extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('percentage', [$this, 'getPercentage']),
            new TwigFilter('currency', [$this, 'getCurrency']),
            new TwigFilter('year', [$this, 'getYear']),
            new TwigFilter('month', [$this, 'getMonth']),
            new TwigFilter('convert', [$this, 'getConvert']),
        ];
    }

    public function getPercentage(float|int $number): string
    {
        return number_format($this->getConvert($number), 2, ',', ' ') . ' %';
    }

    public function getCurrency(float|int $number): string
    {
        return number_format($this->getConvert($number), 0, ',', ' ') . ' €';
    }

    public function getYear(float|int $number): string
    {
        if (1 === $number) {
            return number_format($this->getConvert($number), 0, ',', ' ') . ' an';
        } else {
            return number_format($this->getConvert($number), 0, ',', ' ') . ' ans';
        }
    }

    public function getMonth(float|int $number): string
    {
        return number_format($this->getConvert($number), 0, ',', ' ') . ' mois';
    }

    public function getConvert(float|int $number): float|int
    {
        if (is_float($number) && floor($number) == $number) {
            return (int) $number;
        }
        return $number;
    }
}
