<?php
declare(strict_types=1);

namespace Tokei\Tool\Installer\Database;

use Tokei\Model\Navigation\Item;
use Tokei\Tool\Installer\DatabaseTable;
use Tokei\Tool\Installer\InstallType;
use Tempest\Database\QueryStatements\OnDelete;

#[DatabaseTable(modelClass: Item::class, type: InstallType::INSTALL, after: NavigationCreateTable::class)]
final class NavigationItemCreateTable implements DatabaseCommand
{
    use IsCreateTable;

    protected function setFields(): void
    {
        $this->statement
            ->primary()
            ->varchar('name', default: '')
            ->varchar('target', default: '')
            ->integer('position', default: 0)
            ->boolean('is_active', default: true)
            ->belongsTo('navigation_item.page_id', 'page.id', OnDelete::CASCADE, nullable: true)
            ->belongsTo('navigation_item.navigation_id', 'navigation.id', OnDelete::SET_NULL)
            ->index('navigation_id')
            ->index('page_id')
            ->index('position');
    }
}
