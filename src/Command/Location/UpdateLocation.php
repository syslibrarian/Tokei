<?php

declare(strict_types=1);

namespace Tokei\Command\Location;

use Tokei\Command\Command;
use Tokei\Model\Location\Location;

final class UpdateLocation implements Command
{
    public function __construct(
        public Location $model,
        public string $name,
        public string $seal,
        public string $street,
        public string $city,
        public string $postal_code,
        public float $fte,
        public float $fte_consumed,
        public float $area,
        public string $klrCode,
    ) {}
}
