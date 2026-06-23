<?php

declare(strict_types=1);

namespace Tokei\Command;

use Tempest\Container\Singleton;

#[Singleton]
final class Response
{
    private(set) ?Command $command = null;
    private(set) mixed $value = null;

    public function set(Command $command, mixed $value): static
    {
        $this->command = $command;
        $this->value = $value;

        return $this;
    }

    public function reset(): static
    {
        $this->command = null;
        $this->value = null;

        return $this;
    }
}
