<?php

declare(strict_types=1);

namespace Tokei\Database\Table;

use Tempest\Database\MigratesUp;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;
use Tokei\Model\User\User;

class CreateUserTable implements MigratesUp
{
    public string $name = '2026-10-22_03_create_user_table';

    public function up(): QueryStatement
    {
        return CreateTableStatement::forModel(User::class)
            ->primary()
            ->varchar('username')
            ->varchar('name', default: '')
            ->varchar('surname', default: '')
            ->varchar('email')
            ->varchar('password')
            ->varchar('seal', length: 4, default: '')
            ->belongsTo('user.role_id', 'user_role.id')
            ->index('email')
            ->index('username');
    }
}