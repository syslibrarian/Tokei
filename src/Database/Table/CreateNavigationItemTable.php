<?php

declare(strict_types=1);

namespace Tokei\Database\Table;

use Tempest\Database\MigratesUp;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;
use Tempest\Database\QueryStatements\OnDelete;
use Tokei\Model\Navigation\Item;

class CreateNavigationItemTable implements MigratesUp
{
    public string $name = '2026-10-22_02_create_navigation_item_table';

    public function up(): QueryStatement
    {
        return CreateTableStatement::forModel(Item::class)
            ->primary()
            ->varchar('name', default: '')
            ->varchar('target', default: '')
            ->integer('position', default: 0)
            ->boolean('is_active', default: true)
            ->varchar('need_permission', default: '')
            ->belongsTo('navigation_item.page_id', 'page.id', OnDelete::CASCADE, nullable: true)
            ->belongsTo('navigation_item.navigation_id', 'navigation.id', OnDelete::SET_NULL)
            ->index('navigation_id')
            ->index('page_id')
            ->index('position');
    }
}
