<?php

declare(strict_types=1);

namespace Tokei\Component\Access;

enum AccessContext
{
    case CREATE;
    case UPDATE;
    case DELETE;

    public static function getContext(object|string $object): self
    {
        if (is_object($object)) {
            return self::UPDATE;
        }

        return self::CREATE;
    }

    public static function getClass(object|string $object, ?AccessContext $context = null): string
    {
        if ($context === null) {
            $context = self::getContext($object);
        }

        return match ($context) {
            self::CREATE => CreatePermission::class,
            self::UPDATE => UpdatePermission::class,
            self::DELETE => DeletePermission::class,
        };
    }
}
