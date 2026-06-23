<?php

declare(strict_types=1);

namespace Tokei\Tool\Installer\Database;

use Tokei\Model\Klr\KlrReport;
use Tokei\Tool\Installer\DatabaseTable;
use Tokei\Tool\Installer\InstallType;

#[DatabaseTable(modelClass: KlrReport::class, type: InstallType::INSTALL)]
class CreateKlrMonthTable implements DatabaseCommand
{
    use IsCreateTable;

    protected function setFields(): void
    {
        $this->statement
            ->primary()
            ->integer('report_status', default: 0)
            ->varchar('seal', length: 4)
            ->integer('year')
            ->integer('month')
            ->varchar('time_code', length: 7)
            ->integer('circulations', default: 0)
            ->integer('visits', default: 0)
            ->integer('attendees', default: 0)
            ->integer('created')
            ->integer('reported', default: 0)
            ->integer('modified', default: 0)
            ->index('time_code', 'seal')
            ->index('month', 'seal')
            ->index('year', 'month', 'seal');
    }
}
