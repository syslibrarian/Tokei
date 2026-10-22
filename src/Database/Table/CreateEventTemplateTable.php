<?php

declare(strict_types=1);

namespace Tokei\Database\Table;

use Tempest\Database\MigratesUp;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;
use Tokei\Model\Event\Template;

class CreateEventTemplateTable implements MigratesUp
{
    public string $name = '2026-10-22_8_create_event_template_table';

    public function up(): QueryStatement
    {
        return CreateTableStatement::forModel(Template::class)
            ->primary()
            ->varchar('name', default: '')
            ->varchar('description', default: '')
            ->integer('length');
    }
}
