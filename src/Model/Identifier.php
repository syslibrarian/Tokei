<?php

declare(strict_types=1);

namespace Tokei\Model;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Identifier
{
    public function __construct(
        public string $name,
        public string $template = '',
    ) {}
}
