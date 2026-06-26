<?php

declare(strict_types=1);

namespace Tokei\Component\Access;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class CreatePermission implements Permission
{
    protected(set) int $timeLimit = -1;

    public function __construct(
        protected(set) string $name,
        protected(set) string $super = '',
    ) {}

    public function check(?AccessControl $accessControl, ?object $model = null): bool
    {
        if ($this->name === '') {
            return true;
        }

        return $accessControl->hasPermission($this->name);
    }
}
