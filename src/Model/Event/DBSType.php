<?php

declare(strict_types=1);

namespace Tokei\Model\Event;

use function Tempest\Support\Arr\find_key;

final class DBSType
{
    public const array DBS_NUMBERS = [
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


    public static function exists(mixed $value): bool
    {
        foreach (self::DBS_NUMBERS as $type => $numbers) {
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
        if ($for !== null && array_key_exists($for, self::DBS_NUMBERS)) {
            $temp = self::DBS_NUMBERS[$for];

            foreach ($temp as $value => $name) {
                yield ['name' => $name, 'value' => $value];
            }
        } else {
            foreach (self::DBS_NUMBERS as $group => $numbers) {
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
