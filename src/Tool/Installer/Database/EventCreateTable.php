<?php

declare(strict_types=1);

namespace Tokei\Tool\Installer\Database;

use Tokei\Model\Event\Event;
use Tokei\Tool\Installer\DatabaseTable;
use Tokei\Tool\Installer\InstallType;

#[DatabaseTable(modelClass: Event::class, type: InstallType::INSTALL)]
final class EventCreateTable implements DatabaseCommand
{
    use IsCreateTable;

    protected function setFields(): void
    {
        $this->statement
            ->primary()
            ->varchar('title', default: '')
            ->varchar('description', default: '')
            ->varchar('seal', 4)
            ->varchar('type', default: '')
            ->integer('time_start')
            ->integer('time_end')
            ->varchar('time_code', 7)
            ->float('hours')
            ->integer('staff')
            ->integer('staff_external')
            ->integer('attendees')
            ->integer('online')
            ->integer('state')
            ->string('audience')
            ->integer('is_education')
            ->integer('created')
            ->integer('modified', default: 0)
            ->index('time_start', 'seal', 'is_education')
            ->index('time_code', 'seal', 'is_education')
            ->index('time_start', 'is_education')
            ->index('time_start');
    }
}
