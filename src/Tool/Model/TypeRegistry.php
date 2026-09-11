<?php

declare(strict_types=1);

namespace Tokei\Tool\Model;

use Tempest\Container\Singleton;

#[Singleton]
final class TypeRegistry
{
    /** @var array<array-key, Type> */
    protected array $entries = [];

    public function register(Type $type): self
    {
        $this->entries[$type->name] = $type;
        $this->entries[$type->className] = $type;
        return $this;
    }

    public function getByName(string $name): ?Type
    {
        return $this->entries[$name] ?? null;
    }

    public function getByClass(string|object $class): ?Type
    {
        return $this->entries[is_object($class) ? $class::class : $class] ?? null;
    }
}
