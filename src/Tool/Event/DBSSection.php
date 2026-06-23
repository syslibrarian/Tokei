<?php

declare(strict_types=1);

namespace Tokei\Tool\Event;

final class DBSSection
{
    /** @var array<string, array<string, string>> */
    public const array SECTIONS = [
        FormType::PRE_SCHOOL->value => [
            '95-3' => 'tokei.adm.events.dbs_95-3', // Pre-School
        ],

        FormType::SCHOOL->value => [
            '95-1' => 'tokei.adm.events.dbs_95-1', // Elemantary School
            '95-2' => 'tokei.adm.events.dbs_95-2', // Junior-High & High-School
            '95-4' => 'tokei.adm.events.dbs_95-4', // Other groups
        ],

        FormType::EVENT->value => [
            '96-x' => 'tokei.adm.events.dbs_96-x', // Event for childrens
            '97-x' => 'tokei.adm.events.dbs_97-x', // Event for adults
            '92-x' => 'tokei.adm.events.dbs_92-x', // Social work
            '98-x' => 'tokei.adm.events.dbs_98-x', // Exhibitions
            '99-0' => 'tokei.adm.events.dbs_99-0', // Other types
            '99-1' => 'tokei.adm.events.dbs_99-1', // Guided tours.
        ],
    ];

    public const array INDEX_TO_AUDIENCE = [
        '95-1' => 'young',
        '95-2' => 'young',
        '95-3' => 'young',
        '95-4' => 'adult',
        '96-x' => 'young',
        '97-x' => 'adult',
        '92-x' => 'mixed',
        '98-x' => 'mixed',
        '99-0' => 'mixed',
        '99-1' => 'mixed',
    ];

    public static function getAudience(string $audience, string $sectionNumber): string
    {
        return self::INDEX_TO_AUDIENCE[$sectionNumber] ?? $audience;
    }

    public static function exists(mixed $value): bool
    {
        foreach (self::SECTIONS as $type => $numbers) {
            foreach ($numbers as $number => $desc) {
                if ($value == $number) {
                    return true;
                }
            }
        }

        return false;
    }

    public static function getForForm(?FormType $for = null): \Generator
    {
        if ($for === null || $for === FormType::SYSTEM) {
            foreach (self::SECTIONS as $group => $numbers) {
                foreach ($numbers as $value => $name) {
                    yield ['name' => $name, 'value' => $value];
                }
            }
        } else {
            foreach (self::SECTIONS[$for->value] as $value => $name) {
                yield ['name' => $name, 'value' => $value];
            }
        }
    }

    public static function isEducation(string $sectionNumber): int
    {
        return isset(self::SECTIONS[FormType::PRE_SCHOOL->value][$sectionNumber]) || isset(self::SECTIONS[FormType::SCHOOL->value][$sectionNumber]) ? 1 : 0;
    }
}
