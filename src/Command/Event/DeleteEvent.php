<?php

declare(strict_types=1);

namespace Tokei\Command\Event;

use Tokei\Command\Command;
use Tokei\Model\Event\Event;

final class DeleteEvent implements Command
{
    public function __construct(
        public Event $model,
    ) {}
}
