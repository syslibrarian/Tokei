<?php

declare(strict_types=1);

namespace Tokei\Tool\User;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
class Permission
{
    public function __construct(
        protected(set) string $name,
        protected(set) int $maxHours = -1
    ) {}
}