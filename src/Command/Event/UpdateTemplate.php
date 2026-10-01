<?php

declare(strict_types=1);

namespace Tokei\Command\Event;

use Tokei\Command\Command;
use Tokei\Model\Event\Template;

final class UpdateTemplate implements Command
{
    public function __construct(
        public Template $model,
        public string $name,
        public string $description,
        public int $length,
    ) {}
}
