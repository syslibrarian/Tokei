<?php

declare(strict_types=1);

namespace Tokei\Model\User;

use function Tempest\Database\query;

class RoleHelper
{
    public static function count(): int
    {
        return query(Role::class)->count('id')->execute();
    }

    public static function hasUsers(Role $role): bool
    {
        return query(User::class)
            ->count('id')
            ->where('role_id = ?', $role->id->value)
            ->execute() > 0;
    }

    public static function getForForm(): \Generator
    {
        $roles = Role::select()->all();

        foreach ($roles as $role) {
            yield ['name' => $role->name, 'value' => $role->id->value];
        }
    }
}
