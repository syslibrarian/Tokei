<?php

declare(strict_types=1);

namespace Tokei\Model\Event;

use function Tempest\Support\Arr\find_key;

final class DBSSection
{
    public const array SECTIONS = [
        'education' => [
            '95.1' => 'Klasse 1 - 6', // it is berlin specific.
            '95.2' => 'ab Klasse 7', // it is berlin specific.
            '95.3' => 'Kita',
            '95.4' => 'Sonstige Gruppen'
        ],

        'event' => [
            '96' => 'Für Kinder und Jugendliche (bis 17 Jahre)',
            '97' => 'Für Erwachsene'
        ],

        'other' => [
            '92' => 'Soziale Bibliotheksarbeit',
            '98' => 'Ausstellungen',
            '99' => 'Sonstige Angebote',
            '99.1' => 'Bibliotheksbesichtigungen & Führungen'
        ]
    ];

    public const array INDEX_TO_AUDIENCE = [
        '95.1' => 'young',
        '95.2' => 'young',
        '95.3' => 'young',
        '95.4' => 'adult',
        '96' => 'young',
        '97' => 'adult',
        '92' => '',
        '98' => '',
        '99' => '',
        '99.1' => ''
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
