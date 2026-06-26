<?php

declare(strict_types=1);

namespace Tokei\Tool\Installer\Database;

use Tokei\Model\Navigation\Navigation;
use Tokei\Tool\Installer\DatabaseTable;
use Tokei\Tool\Installer\InstallType;

#[DatabaseTable(modelClass: Navigation::class, type: InstallType::INSTALL)]
final class NavigationCreateTable implements DatabaseCommand
{
    use IsCreateTable;

    protected function setFields(): void
    {
        $this->statement
            ->primary()
            ->varchar('name', 255)
            ->integer('parent_id', nullable: true)
            ->boolean('is_system', default: false)
            ->boolean('is_admin', default: false)
            ->varchar('view_name', 255, default: '_navigation.tpl')
            ->index('name');
    }
}
