<?php

declare(strict_types=1);

namespace Tokei\Tool\Installer\Database;

use Tempest\Database\QueryStatements\OnDelete;
use Tokei\Model\User\Permission;
use Tokei\Tool\Installer\DatabaseTable;
use Tokei\Tool\Installer\InstallType;

#[DatabaseTable(modelClass: Permission::class, type: InstallType::INSTALL, after: UserRoleCreateTable::class)]
final class UserPermissionCreateTable implements DatabaseCommand
{
    use IsCreateTable;

    protected function setFields(): void
    {
        $this->statement
            ->primary()
            ->string('name')
            ->integer('value')
            ->belongsTo('user_permission.role_id', 'user_role.id', OnDelete::CASCADE)
            ->unique('role_id', 'name')
            ->index('role_id');
    }
}
