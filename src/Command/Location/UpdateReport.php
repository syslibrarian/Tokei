<?php

declare(strict_types=1);

namespace Tokei\Command\Location;

use Tokei\Command\Command;
use Tokei\Model\Location\MonthlyReport;

final class UpdateReport implements Command
{
    public function __construct(
        public MonthlyReport $model,
        public int           $circulations,
        public int           $visits,
        public int           $visitsManual,
        public int           $openHours,
        public int           $openLibraryHours,
        public int           $mediaPackages,
        public int           $shifts,
        public int           $coversReceived,
        public int           $coversGiven
    ) {}
}
