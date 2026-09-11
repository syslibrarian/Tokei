<?php

declare(strict_types=1);

namespace Tokei\Model;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final readonly class Routes
{
    public function __construct(
        public string $viewUri,
        public string $listUri,
        public string $createUri,
        public string $updateUri,
        public string $deleteUri,
        public string $prefix = ''
    ) {}
}