<?php

declare(strict_types=1);

namespace Tokei\Command\Location;

use Tokei\Command\Command;

final class CreateLocation implements Command
{
    public function __construct(
        public string $name,
        public string $seal,
        public string $street,
        public string $city,
        public string $postal_code,
        public float $fte,
        public float $fte_consumed,
        public float $area
    ) {}
}
