<?php

declare(strict_types=1);

namespace Tokei\Model\Location;

use Tempest\DateTime\DateTime;
use Tokei\Model\TimeCode;

final class ReportHelper
{
    public static function getLastFor(string $seal): ?MonthlyReport
    {
        $month = DateTime::now()->getMonth();
        $year = DateTime::now()->getYear();

        if ($month === 1) {
            $month = 12;
            $year--;
        } else {
            $month--;
        }

        $timeCode = TimeCode::fromParts($year, $month);

        return MonthlyReport::select()->where('seal = ? AND time_code = ?', $seal, $timeCode)->first();
    }

    /**
     * @param string $seal
     * @return MonthlyReport[]
     */
    public static function getFor(string $seal): array
    {
        return MonthlyReport::select()->where('seal = ? AND year = ?', $seal, DateTime::now()->getYear())->all();
    }

    public static function getSealSorted(): array
    {
        $reports = MonthlyReport::select()->where('year = ?', DateTime::now()->getYear())->all();
        $sorted = [];
        foreach ($reports as $report) {
            $sorted[$report->seal][$report->month] = $report;
        }

        return $sorted;
    }
}
