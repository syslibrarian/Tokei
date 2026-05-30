<?php

declare(strict_types=1);

namespace Tokei\Tool\Installer\Database;

use Tokei\Model\User\User;
use Tokei\Tool\Installer\DatabaseTable;
use Tokei\Tool\Installer\InstallType;

#[DatabaseTable(modelClass: User::class, type: InstallType::INSTALL, after: UserRoleCreateTable::class)]
final class UserCreateTable implements DatabaseCommand
{
    use IsCreateTable;

    protected function setFields(): void
    {
        $this->statement
            ->primary()
            ->varchar('username')
            ->varchar('name', default: '')
            ->varchar('surname', default: '')
            ->varchar('email')
            ->varchar('password')
            ->belongsTo('user.role_id', 'user_role.id')
            ->index('email')
            ->index('username');
    }
}
