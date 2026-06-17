<?php

declare(strict_types=1);

namespace Tokei\Component\Access;

enum AccessContext
{
    case CREATE;
    case UPDATE;
    case DELETE;

    public static function getClass(?AccessContext $context = null, object|string $object): ?string
    {
        if ($context === null && is_string($object)) {
            return CreatePermission::class;
        }

        if ($context === null) {
            return UpdatePermission::class;
        }

        return match (true) {
            $context === self::CREATE => CreatePermission::class,
            $context === self::UPDATE => UpdatePermission::class,
            $context === self::DELETE => DeletePermission::class,
            default => null
        };
    }
}
