<?php

declare(strict_types=1);

namespace Tokei\Component\Revision;

use Twig\Environment;

use function Tempest\Container\get;

trait IsRevisionManager
{
    protected function __construct(
        protected(set) string|object $modelClass,
        protected(set) Environment $twig,
    ) {}

    public static function forModel(string|object $modelClass): static|false
    {
        return new static($modelClass, get(Environment::class));
    }
}
