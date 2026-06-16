<?php

declare(strict_types=1);

namespace Tokei\Model\Event;

use Tempest\Database\Direction;
use Tempest\DateTime\DateTime;
use Tempest\DateTime\Timezone;

final class EventHelper
{
    public const array STATE = [
        1 => 'occurred',
        2 => 'canceled',
        3 => 'absent',
        4 => 'error',
    ];

    public const array ONLINE = [
        1 => 'tokei.adm.events.online_normal',
        2 => 'tokei.adm.events.online_hybrid',
        3 => 'tokei.adm.events.online_only'
    ];

    public const array AUDIENCE = ['young', 'adult'];

    public static function isNormal(Event $event): bool
    {
        return $event->state === 1;
    }

    public static function isCanceled(Event $event): bool
    {
        return $event->state === 2;
    }

    public static function isAbsent(Event $event): bool
    {
        return $event->state === 3;
    }

    public static function convertToDateTime(string $dateFromForm): int
    {
        $dateFromForm = str_replace('T', ' ', $dateFromForm);
        return DateTime::fromPattern($dateFromForm, 'yyyy-MM-dd HH:mm')->getTimestamp()->getSeconds();
    }

    public static function getEventsByPeriod(?string $seal = '', ?int $startTime = null, ?int $endTime = null): array
    {
        // now startime - last 30 days.
        if ($startTime === null) {
            $startTime = DateTime::now()->minusDays(30)->getTimestamp()->getSeconds();
            $endTime = null;
        }

        // ... but it is safe!
        if ($endTime !== null && $endTime < $startTime) {
            $tmp = $startTime;
            $startTime = $endTime;
            $endTime = $tmp;
        }

        if ($endTime !== null) {
            $eventRaw = Event::select()->where('time_start BETWEEN ? and ?', $startTime, $endTime)->orderBy('time_start', Direction::DESC);
        } else {
            $eventRaw = Event::select()->where('time_start >= ?', $startTime)->orderBy('time_start', Direction::DESC);
        }

        return $eventRaw->andWhere('seal', $seal)->all();
    }

    public static function calculateEnd(int $startTime, string $endTime): int
    {
        $startDateTime = DateTime::fromTimestamp($startTime);

        if (preg_match('#^\+([0-9]{2,3})$#u', $endTime, $timeFactor)) {
            $factor = (int) $timeFactor[1];
            return $startDateTime->getTimestamp()->getSeconds() + ($factor * 60);
        }

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
            yield ['value' => $value, 'name' => 'tokei.adm.events.status_' . $name];
        }
    }

    public static function getOnlineForForm(): \Generator
    {
        foreach (self::ONLINE as $value => $name) {
            yield ['value' => $value, 'name' => $name];
        }
    }

    public static function getAudienceForForm(): \Generator
    {
        foreach (self::AUDIENCE as $value) {
            yield ['value' => $value, 'name' => 'tokei.adm.events.audience_' . $value];
        }
    }
}
