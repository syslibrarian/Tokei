<?php

declare(strict_types=1);

namespace Tokei\Tool\Installer\Database;

use Tempest\Database\Enums\DatabaseTextLength;
use Tokei\Model\Location\Report;
use Tokei\Tool\Installer\DatabaseTable;
use Tokei\Tool\Installer\InstallType;

#[DatabaseTable(modelClass: Report::class, type: InstallType::INSTALL)]
final class ReportCreateTable implements DatabaseCommand
{
    use IsCreateTable;

    protected function setFields(): void
    {
        $this->statement->primary()
            ->integer('status', default: 1)
            ->varchar('seal', 4)
            ->varchar('time_code', length: 7)
            ->integer('year')
            ->integer('month')
            ->integer('circulations', default: 0) // KLR/GLD => Ausleihen
            ->integer('visits', default: 0)
            ->integer('visits_manual', default: 0)
            ->integer('open_hours', default: 0)
            ->integer('open_library_hours', default: 0)
            ->integer('media_packages', default: 0)
            ->integer('shifts', default: 0)
            ->integer('overs_received', default: 0)
            ->integer('covers_given', default: 0)
            ->text('events_raw', length: DatabaseTextLength::MEDIUM)
            ->integer('created')
            ->integer('modified', default: 0)
            ->integer('updated', default: 0)
            ->unique('time_code', 'seal')
            ->index('month', 'year', 'seal')
            ->index('seal');
    }
}
