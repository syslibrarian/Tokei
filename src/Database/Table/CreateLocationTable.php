<?php

declare(strict_types=1);

namespace Tokei\Database\Table;

use Tempest\Database\MigratesUp;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;
use Tokei\Model\Location\Location;

class CreateLocationTable implements MigratesUp
{
    public string $name = '2026-10-22_06_create_location_table';

    public function up(): QueryStatement
    {
        return CreateTableStatement::forModel(Location::class)
            ->primary()
            ->varchar('name')
            ->varchar('seal', 4)
            ->varchar('klr_code')
            ->varchar('street')
            ->varchar('city')
            ->varchar('postal_code', 5)
            ->float('fte')
            ->float('fte_consumed')
            ->float('area')
            ->integer('created')
            ->integer('modified', default: 0)
            ->index('seal');
    }
}