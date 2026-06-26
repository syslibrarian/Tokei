<?php

declare(strict_types=1);

namespace Tokei\Model;

enum ReportStatus: int
{
    case OPEN = 1; // Report/Klr needs data.
    case CLOSE = 2; // Report/Klr finished and read for print.
    case UPDATED = 3; // Report/KLR corrections.

    public static function isOpen(int $value): bool
    {
        return $value === self::OPEN->value;
    }

    public static function isClose(int $value): bool
    {
        return $value === self::CLOSE->value;
    }

    public static function isUpdated(int $value): bool
    {
        return $value === self::UPDATED->value;
    }
}
