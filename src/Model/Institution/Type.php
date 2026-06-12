<?php

declare(strict_types=1);

namespace Tokei\Model\Institution;

final class Type
{
    protected static $types = [
        1 => 'tokei.adm.events.institution.type_pre_school',
        2 => 'tokei.adm.events.institution.type_school'
    ];

    public static function checkType(int $type): bool
    {
        return array_key_exists($type, self::$types);
    }

    public static function getForForm(): \Generator
    {
        foreach (static::$types as $key => $name) {
            yield ['name' => $name, 'value' => $key];
        }
    }
}
