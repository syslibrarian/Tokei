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

    public static function getLocationsForForm(): \Generator
    {
        $locations = Location::all();
        foreach ($locations as $location) {
            yield ['name' => $location->name, 'value' => $location->seal];
        }
    }

    public static function getAllReportsForCommand(string|int $year): array
    {
        $sortedReports = [];
        $reports = Report::select()->where('year = ?', $year)->all();

        foreach ($reports as $report) {
            $sortedReports[(int)$report->month][$report->seal] = $report;
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
