<?php

declare(strict_types=1);

namespace Tokei\Database\Table;

use Tempest\Database\Enums\DatabaseTextLength;
use Tempest\Database\MigratesUp;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;
use Tokei\Model\Report;

class CreateReportTable implements MigratesUp
{
    public string $name = '2026-10-22_10_create_report_table';

    public function up(): QueryStatement
    {
        return CreateTableStatement::forModel(Report::class)
            ->primary()
            ->integer('report_status', default: 1)
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
            ->integer('covers_received', default: 0)
            ->integer('covers_given', default: 0)
            ->integer('staff_external', default: 0)
            ->float('staff_external_hours', default: 0)
            ->integer('staff_volunteer', default: 0)
            ->float('staff_volunteer_hours', default: 0)
            ->integer('staff_grant', default: 0)
            ->float('staff_grant_hours', default: 0)
            ->text('events_raw', length: DatabaseTextLength::MEDIUM, default: '')
            ->integer('created')
            ->integer('modified', default: 0)
            ->integer('updated', default: 0)
            ->unique('time_code', 'seal')
            ->index('month', 'year', 'seal')
            ->index('seal');
    }
}

