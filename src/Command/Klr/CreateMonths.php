<?php

declare(strict_types=1);

namespace Tokei\Command\Klr;

use Tokei\Command\Command;

final class CreateMonths implements Command
{
    public function __construct(
        public int|string $year
    ) {}
}
