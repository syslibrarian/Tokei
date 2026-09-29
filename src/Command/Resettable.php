<?php

declare(strict_types=1);

namespace Tokei\Command;

interface Resettable extends Command
{
    public function reset(...$overrideDefaults): void;
}
