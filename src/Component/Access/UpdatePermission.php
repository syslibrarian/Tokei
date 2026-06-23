<?php

declare(strict_types=1);

namespace Tokei\Component\Access;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class UpdatePermission implements Permission
{
    public function __construct(
        protected(set) string $name,
        protected(set) int $timeLimit = -1,
        protected(set) string $super = 'can_update_limitless',
    ) {}
}
