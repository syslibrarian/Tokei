<?php

declare(strict_types=1);

namespace Tokei\Command\Event;

use Tokei\Command\Command;
use Tokei\Command\IsResetable;

final class CreateEvent implements Command
{
    use IsResetable;

    public function __construct(
        public string $seal = '',
        public string $type = '',
        public string $startDateTime = '',
        public string $endTime = '',
        public int $staff = 0,
        public int $staff_external = 0,
        public int $attendees = 0,
        public int $online = 1,
        public int $state = 1,
        public string $title = '',
        public string $description = '',
        public string $audience = ''
    ) {}
}
