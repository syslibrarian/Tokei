<?php

declare(strict_types=1);

namespace Tokei\Database\Table;

use Tempest\Database\MigratesUp;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;
use Tokei\Model\Event\Event;

class CreateEventTable implements MigratesUp
{
    public string $name = '2026-10-22_07_create_event_table';

    public function up(): QueryStatement
    {
        return CreateTableStatement::forModel(Event::class)
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
