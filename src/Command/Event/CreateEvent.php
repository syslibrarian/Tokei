<?php

declare(strict_types=1);

namespace Tokei\Command\Event;

use Tokei\Command\Command;
use Tokei\Command\IsResettable;
use Tokei\Command\Resettable;
use Tokei\Model\Event\Event;

final class CreateEvent implements Resettable
{
    use IsResettable;
    public string $modelClass = Event::class;

    public function __construct(
        public string $seal = '',
        public string $type = '',
        public string $startDateTime = '',
        public string $endTime = '',
        public int $staff = 0,
        public int $staffExternal = 0,
        public int $attendees = 0,
        public int $online = 1,
        public int $state = 1,
        public string $title = '',
        public string $description = '',
        public string $audience = '',
    ) {}
}
