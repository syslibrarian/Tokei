<?php

declare(strict_types=1);

namespace Tokei\Model\Event;

use Tempest\DateTime\DateTime;
use Tempest\DateTime\Timestamp;
use Tempest\DateTime\Timezone;

final class EventHelper
{
    public const array STATE = [
        1 => 'occurred',
        2 => 'canceled',
        3 => 'absent'
    ];

    public const array ONLINE = [
        1 => 'normal',
        2 => 'hybrid',
        3 => 'only'
    ];

    public static function buildTimeCode(int $timestamp): string
    {
        $time = DateTime::fromTimestamp($timestamp);

        return $time->getYear() . '-' . $time->getMonth();
    }

    public static function convertToDateTime(string $dateFromForm): int
    {
        $dateFromForm = str_replace('T', ' ', $dateFromForm);
        return DateTime::fromPattern($dateFromForm, 'yyyy-MM-dd HH:mm')->getTimestamp()->getSeconds();
    }

    public static function calculateEnd(int $startTime, string $endTime): int
    {
        $startDateTime = DateTime::fromTimestamp($startTime);
        list($hour, $minute) = explode(':', $endTime); // pregmatch later.

        $endDateTime = DateTime::fromParts(
            Timezone::default(),
            $startDateTime->getYear(),
            $startDateTime->getMonth(),
            $startDateTime->getDay(),
            (int) $hour,
            (int) $minute
        );

        return $endDateTime->getTimestamp()->getSeconds();

    }

    public static function calculateHours(int $startTime, int $endTime): float
    {
        $time = $endTime - $startTime;
        $hours = floor($time / 3600);
        $minutes = floor($time / 60 % 60);

        $min = fn ($minutes) => match(true) {
            $minutes >= 53 => 1,
            $minutes >= 38 => 0.75,
            $minutes >= 23 => 0.5,
            $minutes >= 8 => 0.25,
            default => 0
        };

        return $hours + $min($minutes);
    }

    public static function getStateForForm(): \Generator
    {
        foreach (self::STATE as $value => $name) {
            yield ['value' => $value, 'name' => $name];
        }
    }

    public static function getOnlineForForm(): \Generator
    {
        foreach (self::ONLINE as $value => $name) {
            yield ['value' => $value, 'name' => $name];
        }
    }
}
