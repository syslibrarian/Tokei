<?php

declare(strict_types=1);

namespace Tokei\Tool\Model;

final readonly class Type
{
    public function __construct(
        public string $name,
        public string $className,
        public string $template,
    ) {}
}
