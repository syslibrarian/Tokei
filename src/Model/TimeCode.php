<?php

declare(strict_types=1);

namespace Tokei\Model;

use Tempest\DateTime\DateTime;

final class TimeCode
{

    private function __construct(protected(set) string $timeCode)
    {}

    public function __toString(): string
    {
        return $this->timeCode;
    }

    public static function fromTimestamp(int $timestamp): TimeCode
    {
        $time = DateTime::fromTimestamp($timestamp);

        return self::fromParts($time->getYear(), $time->getMonth());
    }

    public static function fromString(string $timeCode): ?TimeCode
    {
        return self::isValid($timeCode) ? new self($timeCode) : null;
    }

    public static function fromParts(string|int $year, string|int $month): TimeCode
    {
        return new self(sprintf('%04d-%02d', $year, $month));
    }

    public static function isValid(string $timeCode): bool
    {
        return (bool) preg_match('#^\d{4}-\d{2}$#', $timeCode);
    }
}
