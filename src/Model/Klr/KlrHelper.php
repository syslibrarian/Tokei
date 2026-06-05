<?php

declare(strict_types=1);

namespace Tokei\Model\Klr;

final class KlrHelper
{
    public static function getAllMonthsForCommand(string|int $year): array
    {
        $sortedMonths = [];
        $months = Month::select()->where('year = ?', $year)->all();

        foreach ($months as $month) {
            $sortedMonths[(int) $month->month][$month->seal] = $month;
        }

        return $sortedMonths;
    }

    public static function getSortedMonths(string $timeCode): array
    {
        $sortedMonths = [];
        $months = Month::select()->where('timeCode = ?', $timeCode)->all();

        foreach ($months as $month) {
            $sortedMonths[$month->seal] = $month;
        }

        return $sortedMonths;
    }
}
