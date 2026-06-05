<?php

declare(strict_types=1);

namespace Tokei\Command;

use Tempest\Container\Singleton;

#[Singleton]
final class Response
{
    protected(set) ?Command $command = null;
    protected(set) mixed $value = null;

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
