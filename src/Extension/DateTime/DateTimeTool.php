<?php

declare(strict_types=1);

namespace Tokei\Extension\DateTime;

use Tempest\Container\Singleton;
use Tempest\DateTime\DateTime;
use Tempest\DateTime\Timezone;

#[Singleton]
final class DateTimeTool
{
    public function __construct(
        protected(set) DateTimeSettings $settings,
    ) {}

    public function fromTimestamp(int $timestamp, bool $useUTC = false): DateTime
    {
        return DateTime::fromTimestamp($timestamp, ($useUTC) ? Timezone::UTC : $this->settings->timezone);
    }

    public function fromInputString(string $string, bool $useUTC = false): DateTime
    {
        $string = str_replace('T', ' ', $string);

        if (strlen($string) === 10) {
            $string .= ' 00:00';
        }

        if (strlen($string) === 19) {
            $string = substr($string, 0, 16);
        }

        return DateTime::fromPattern($string, 'yyyy-MM-dd HH:mm', ($useUTC) ? Timezone::UTC : $this->settings->timezone);
    }

    public function format(int $timestamp, string $format, bool $useUTC = false): string
    {
        return $this->fromTimestamp($timestamp, $useUTC)->format($format);
    }

    public function formatLong(int $timestamp, bool $useUTC = false): string
    {
        return $this->format($timestamp, $this->settings->longFormat, $useUTC);
    }

    public function formatShort(int $timestamp, bool $useUTC = false): string
    {
        return $this->format($timestamp, $this->settings->shortFormat, $useUTC);
    }

    public function formatTime(int $timestamp, bool $useUTC = false): string
    {
        return $this->format($timestamp, $this->settings->timeFormat, $useUTC);
    }

    public function formatForInput(int $timestamp, bool $useUTC = false): string
    {
        return str_replace(' ', 'T', $this->format($timestamp, 'yyyy-MM-dd HH:mm', $useUTC));
    }
}
