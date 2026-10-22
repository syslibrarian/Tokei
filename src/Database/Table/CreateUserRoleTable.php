<?php

declare(strict_types=1);

namespace Tokei\Database\Table;

use Tempest\Database\MigratesUp;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;
use Tokei\Model\User\Role;

class CreateUserRoleTable implements MigratesUp
{
    public string $name = '2026-10-22_04_create_user_role_table';

    public function up(): QueryStatement
    {
        return CreateTableStatement::forModel(Role::class)
            ->primary()
            ->varchar('name')
            ->integer('system', default: 0)
            ->unique('name');
    }
}
