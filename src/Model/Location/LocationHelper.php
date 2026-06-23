<?php

declare(strict_types=1);

namespace Tokei\Model\Location;

use function Tempest\Database\query;

final class LocationHelper
{
    public static function isExistingSeal(string $seal): bool
    {
        return query(Location::class)
            ->count('id')
            ->where('seal = ?', $seal)
            ->execute() > 0;
    }

    public static function getLocationsForForm(bool $withBase = false): \Generator
    {
        $locations = Location::all();
        foreach ($locations as $location) {
            yield ['name' => $location->name, 'value' => $location->seal];
        }

        yield ['name' => 'tokei.adm.location.for_all', 'value' => 'x'];
    }

    public static function getAllReportsForCommand(string|int $year): array
    {
        $sortedReports = [];
        $reports = MonthlyReport::select()->where('year = ?', $year)->all();

        foreach ($reports as $report) {
            $sortedReports[(int) $report->month][$report->seal] = $report;
        }

        return $sortedReports;
    }

    public static function getLocationsForReports(): array
    {
        $locationsSorted = [];
        $locations = Location::all();

        foreach ($locations as $location) {
            $locationsSorted[$location->seal] = $location;
        }

        return $locationsSorted;
    }
}
