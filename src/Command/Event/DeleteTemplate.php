<?php

declare(strict_types=1);

namespace Tokei\Command\Event;

use Tokei\Command\Command;
use Tokei\Model\Event\Template;

final class DeleteTemplate implements Command
{
    public function __construct(
        public Template $model,
    ) {}
}
