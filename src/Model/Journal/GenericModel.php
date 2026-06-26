<?php

declare(strict_types=1);

namespace Tokei\Model\Journal;

use function Tempest\Support\Json\decode;

/**
 * @template TModel of object
 * @mixin TModel
 */
final class GenericModel
{
    /**
     * @var array<string, mixed>
     */
    private(set) array $data = [];

    /**
     * @param int $id
     * @param class-string<TModel> $objectClass
     * @param string $data
     */
    public function __construct(
        protected(set) int $id,
        protected(set) string $objectClass,
        string $data,
    ) {
        $this->data = decode($data);
    }

    public function __get(string $name): mixed
    {
        return $this->data[$name] ?? null;
    }

    public function __isset(string $name): bool
    {
        return isset($this->data[$name]);
    }

    public function __set(string $name, mixed $value): void
    {
        throw new \RuntimeException('GenericModel is read-only');
    }
}
