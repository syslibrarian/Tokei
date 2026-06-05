<?php

declare(strict_types=1);

namespace Tokei\Command\Location;

use Tokei\Command\Command;

final class CreateReports implements Command
{
    public function __construct(
        public string|int $year
    ) {}
}
