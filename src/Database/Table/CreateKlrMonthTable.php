<?php

declare(strict_types=1);

namespace Tokei\Database\Table;

use Tempest\Database\MigratesUp;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;
use Tokei\Model\Klr\KlrReport;

class CreateKlrMonthTable implements MigratesUp
{
    public string $name = '2026-10-22_11_create_klr_month_table';

    public function up(): QueryStatement
    {
        return CreateTableStatement::forModel(KlrReport::class)
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
