<?php

declare(strict_types=1);

namespace Tokei\Database\Table;

use Tempest\Database\MigratesUp;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;
use Tokei\Model\Institution\Institution;

class CreateInstitutionCreateTable implements MigratesUp
{
    public string $name = '2026-10-22_9_create_institution_create_table';

    public function up(): QueryStatement
    {
        return CreateTableStatement::forModel(Institution::class)
            ->primary()
            ->varchar('name')
            ->varchar('educator')
            ->varchar('type', length: 10)
            ->varchar('email', default: '')
            ->varchar('phone', default: '')
            ->varchar('seal', 4)
            ->integer('created')
            ->integer('modified', default: 0)
            ->integer('last_event', default: 0)
            ->varchar('postal_code', length: 5)
            ->index('seal', 'type');
    }
}
