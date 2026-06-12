<?php

declare(strict_types=1);

namespace Tokei\Model\Event;

use function Tempest\Support\Arr\find_key;

final class DBSSection
{
    /** @var array<string, array<string, string>> */
    public const array SECTIONS = [
        'education' => [
            '95-1' => 'tokei.adm.events.dbs_95-1', // it is berlin specific.
            '95-2' => 'tokei.adm.events.dbs_95-2', // it is berlin specific.
            '95-3' => 'tokei.adm.events.dbs_95-3',
            '95-4' => 'tokei.adm.events.dbs_95-4'
        ],

        'event' => [
            '96-x' => 'tokei.adm.events.dbs_96-x',
            '97-x' => 'tokei.adm.events.dbs_97-x'
        ],

        'other' => [
            '92-x' => 'tokei.adm.events.dbs_92-x',
            '98-x' => 'tokei.adm.events.dbs_98-x',
            '99-0' => 'tokei.adm.events.dbs_99-0',
            '99-1' => 'tokei.adm.events.dbs_99-1'
        ]
    ];

    public const array INDEX_TO_AUDIENCE = [
        '95-1' => 'young',
        '95-2' => 'young',
        '95-3' => 'young',
        '95-4' => 'adult',
        '96-x' => 'young',
        '97-x' => 'adult',
        '92-x' => '',
        '98-x' => '',
        '99-x' => '',
        '99-1' => ''
    ];

    public static function getAudience(string $audience, string $sectionNumber): string
    {
        if (isset(self::INDEX_TO_AUDIENCE[$sectionNumber]) && self::INDEX_TO_AUDIENCE[$sectionNumber] !== '') {
            return self::INDEX_TO_AUDIENCE[$sectionNumber];
        }

        return $audience;
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

    public static function getForForm(?string $for = null): \Generator
    {
        if ($for !== null && array_key_exists($for, self::SECTIONS)) {
            $temp = self::SECTIONS[$for];

            foreach ($temp as $value => $name) {
                yield ['name' => $name, 'value' => $value];
            }
        } else {
            foreach (self::SECTIONS as $group => $numbers) {
                foreach ($numbers as $value => $name) {
                    yield ['name' => $name, 'value' => $value];
                }
            }
        }
    }

    public static function getForFormEducation(): \Generator
    {
        return self::getForForm('education');
    }

    public static function getForFormEvent(): \Generator
    {
        return self::getForForm('event');
    }

    public static function getForFormOther(): \Generator
    {
        return self::getForForm('other');
    }
}
