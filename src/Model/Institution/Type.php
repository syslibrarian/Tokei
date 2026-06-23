<?php

declare(strict_types=1);

namespace Tokei\Model\Institution;

use Tokei\Tool\Event\FormType;

final class Type
{
    private static array $types = [
        FormType::PRE_SCHOOL->value => 'tokei.adm.institution.type_pre_school',
        FormType::SCHOOL->value => 'tokei.adm.institution.type_school',
    ];

    public static function checkType(string $type): bool
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
