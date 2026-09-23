<?php

declare(strict_types=1);

namespace Tokei\Extension\DateTime;

use Tempest\Container\Singleton;
use Tempest\DateTime\Timezone;

#[Singleton]
final class DateTimeSettings
{
    public function __construct(
        protected(set) Timezone $timezone = Timezone::EUROPE_BERLIN,
        protected(set) string   $longFormat = 'dd.MM.yyyy, HH:mm',
        protected(set) string   $shortFormat = 'dd.MM.yyyy',
        protected(set) string   $timeFormat = 'HH:mm',
    ) {}

    public function changeTimezone(Timezone $timezone): void
    {
        $this->timezone = $timezone;
    }

    public function changeFormat(?string $longFormat, ?string $shortFormat, ?string $timeFormat): void
    {
        $this->longFormat = $longFormat ?? $this->longFormat;
        $this->shortFormat = $shortFormat ?? $this->shortFormat;
        $this->timeFormat = $timeFormat ?? $this->timeFormat;
    }
}