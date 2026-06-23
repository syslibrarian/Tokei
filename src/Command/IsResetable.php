<?php

declare(strict_types=1);

namespace Tokei\Command;

trait IsResetable
{
    public function reset(...$overrideDefaults): void
    {
        $reflector = new \ReflectionClass($this);
        $parameters = $reflector->getMethod('__construct')->getParameters();
        foreach ($parameters as $parameter) {
            if (! $parameter->isDefaultValueAvailable()) {
                throw new \RuntimeException('Resetable *Command must implement default values for parameters');
            }

            $this->{$parameter->getName()} = $overrideDefaults[$parameter->getName()] ?? $parameter->getDefaultValue();
        }
    }
}
