<?php

declare(strict_types=1);

namespace Tokei\Database\Table;

use Tempest\Database\MigratesUp;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;
use Tokei\Model\Navigation\Navigation;

class CreateNavigationTable implements MigratesUp
{
    public string $name = '2026-10-26_01_create_navigation_table';

    public function up(): QueryStatement
    {
        return CreateTableStatement::forModel(Navigation::class)
            ->primary()
            ->varchar('name', 255)
            ->integer('parent_id', nullable: true)
            ->boolean('is_system', default: false)
            ->boolean('is_admin', default: false)
            ->varchar('view_name', 255, default: '_navigation.tpl')
            ->index('name');
    }
}
