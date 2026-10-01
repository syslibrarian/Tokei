<?php

declare(strict_types=1);

namespace Tokei\Command\Event;

use Tokei\Command\IsResettable;
use Tokei\Command\Resettable;

final class CreateTemplate implements Resettable
{
    use IsResettable;

    public function __construct(
        public string $name,
        public string $description,
        public int $length,
    ) {}
}
