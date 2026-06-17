<?php

declare(strict_types=1);

namespace Tokei\Component\Access;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class DeletePermission implements Permission
{
    protected(set) int $timeLimit = -1;

    public function __construct(
        protected(set) string $name = 'can_delete',
        protected(set) string $super = 'can_delete'
    ) {}
}
