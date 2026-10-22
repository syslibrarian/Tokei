<?php

declare(strict_types=1);

namespace Tokei\Database\Table;

use Tempest\Database\MigratesUp;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;
use Tempest\Database\QueryStatements\OnDelete;
use Tokei\Model\User\Permission;

class CreateUserPermissionTable implements MigratesUp
{
    public string $name = '2026-10-22_05_create_user_permission_table';

    public function up(): QueryStatement
    {
        return CreateTableStatement::forModel(Permission::class)
            ->primary()
            ->string('name')
            ->integer('value')
            ->belongsTo('user_permission.role_id', 'user_role.id', OnDelete::CASCADE)
            ->unique('role_id', 'name')
            ->index('role_id');
    }
}
