<?php

declare(strict_types=1);

namespace Tokei\Command;

trait IsResetable
{
    public function reset(): void
    {
        $reflector = new \ReflectionClass($this);
        $parameters = $reflector->getMethod('__construct')->getParameters();

        foreach ($parameters as $parameter) {
            $this->{$parameter->getName()} = $parameter->getDefaultValue();
        }
    }
}
