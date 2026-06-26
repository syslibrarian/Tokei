<?php

declare(strict_types=1);

namespace Tokei\Tool\Statistic;

use Tokei\Model\ReportStatus;

final class Cell
{
    public function __construct(
        public int $value,
        public int $status,
    ) {}

    public function empty(): bool
    {
        return $this->value === 0 && ReportStatus::isOpen($this->status);
    }

    public function marked(): bool
    {
        return ReportStatus::isUpdated($this->status);
    }
}
