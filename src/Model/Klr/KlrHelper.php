<?php

declare(strict_types=1);

namespace Tokei\Model\Klr;

final class KlrHelper
{
    public const array KLR_PRODUCTS = [
        '80035' => [
            'title' => 'tokei.klr.product_80035', // Teilnehmende [E26] - Produkt: 80035'
            'field' => 'attendees'
        ],

        '80007' => [
            'title' => 'tokei.klr.product_80007', // Entleihungen [B8] - Produkt: 80007'
            'field' => 'circulations'
        ],

        '80008' => [
            'title' => 'tokei.klr.product_80008', // Besuche [B6] - Produkt: 80008'
            'field' => 'visits'
        ]
    ];

    public static function getAllMonthsForCommand(string|int $year): array
    {
        $sortedMonths = [];
        $months = KlrReport::select()->where('year = ?', $year)->all();

        foreach ($months as $month) {
            $sortedMonths[(int) $month->month][$month->seal] = $month;
        }

        return $sortedMonths;
    }

    public static function getSortedMonths(string $timeCode): array
    {
        $sortedMonths = [];
        $months = KlrReport::select()->where('time_code = ?', $timeCode)->all();

        foreach ($months as $month) {
            $sortedMonths[$month->seal] = $month;
        }

        return $sortedMonths;
    }
}
