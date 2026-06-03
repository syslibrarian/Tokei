<?php

declare(strict_types=1);

namespace Tokei\Command\Event;

use Tokei\Command\Command;
use Tokei\Model\Event\Event;

final class UpdateEvent implements Command
{
    public function __construct(
        public Event $model,
        public string $seal,
        public string $type,
        public string $startDateTime,
        public string $endTime,
        public int $staff,
        public int $staff_external,
        public int $attendees,
        public int $online,
        public int $state,
        public string $title,
        public string $description,
        public string $audience
    ) {}
}
