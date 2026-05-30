<?php
declare(strict_types=1);

namespace Tokei\Tool\Installer\Database;

use Tokei\Model\User\Role;
use Tokei\Tool\Installer\DatabaseTable;
use Tokei\Tool\Installer\InstallType;

#[DatabaseTable(modelClass: Role::class, type: InstallType::INSTALL)]
final class UserRoleCreateTable implements DatabaseCommand
{
    use IsCreateTable;

    protected function setFields(): void
    {
        $this->statement
            ->primary()
            ->varchar('name')
            ->integer('system', default: 0)
            ->unique('name');
    }
}
