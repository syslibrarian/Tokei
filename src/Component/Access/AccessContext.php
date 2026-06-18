<?php

declare(strict_types=1);

namespace Tokei\Component\Access;

enum AccessContext
{
    case CREATE;
    case UPDATE;
    case DELETE;

    public static function getClass(object|string $object, ?AccessContext $context = null): string
    {
        if ($context === null && is_string($object)) {
            return CreatePermission::class;
        }

        if ($context === null) {
            return UpdatePermission::class;
        }

        return match ($context) {
            self::CREATE => CreatePermission::class,
            self::UPDATE => UpdatePermission::class,
            self::DELETE => DeletePermission::class,
            default => 'never',
        };
    }
}
