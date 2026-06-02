<?php

declare(strict_types=1);

namespace Tokei\Model\Location;

use function Tempest\Database\query;

class LocationHelper
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
}
