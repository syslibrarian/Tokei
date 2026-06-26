<?php

declare(strict_types=1);

namespace Tokei\Model\User;

use Tempest\Auth\Authentication\Authenticatable;
use Tempest\Database\BelongsTo;
use Tempest\Database\Hashed;
use Tempest\Database\IsDatabaseModel;
use Tempest\Database\Table;
use Tempest\Mapper\Hidden;
use Tempest\Validation\Rules\IsEmail;
use Tempest\Validation\Rules\IsNotEmptyString;
use Tokei\Component\Access\CreatePermission;
use Tokei\Component\Access\DeletePermission;
use Tokei\Component\Access\UpdatePermission;
use Tokei\Extension\Validation\Rules\IsExistingEmail;
use Tokei\Extension\Validation\Rules\IsExistingSeal;
use Tokei\Extension\Validation\Rules\IsExistingUsername;

#[
    Table('user'),
    CreatePermission('can_create_role'),
    UpdatePermission('can_update_role'),
    DeletePermission,
]
final class User implements Authenticatable
{
    use IsDatabaseModel;

    #[IsNotEmptyString, IsExistingUsername]
    public string $username;

    public string $surname = '';
    public string $name = '';

    #[Hashed, Hidden]
    public string $password;

    #[IsNotEmptyString, IsEmail, IsExistingEmail, Hidden]
    public string $email;

    #[BelongsTo(relationJoin: 'user_role.id', ownerJoin: 'user.role_id')]
    public ?Role $role;

    #[IsExistingSeal]
    public string $seal = '';

    public int $role_id;
}
