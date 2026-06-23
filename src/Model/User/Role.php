<?php

declare(strict_types=1);

namespace Tokei\Model\User;

use Tempest\Database\HasMany;
use Tempest\Database\IsDatabaseModel;
use Tempest\Database\Table;
use Tempest\Database\Virtual;
use Tempest\Validation\Rules\HasLength;
use Tempest\Validation\Rules\IsNotEmptyString;
use Tokei\Component\Access\CreatePermission;
use Tokei\Component\Access\DeletePermission;
use Tokei\Component\Access\UpdatePermission;
use Tokei\Extension\Validation\Rules\IsNotExistingRole;

#[
    Table(name: 'user_role'),
    CreatePermission('can_create_role'),
    UpdatePermission('can_update_role'),
    DeletePermission,
]
final class Role
{
    use IsDatabaseModel;

    #[IsNotEmptyString, IsNotExistingRole, HasLength(1, 255)]
    public string $name;

    /** @var \Tokei\Model\User\Permission[] $permissions */
    #[HasMany(ownerJoin: 'user_permission.role_id', relationJoin: 'user_role.id')]
    public array $permissions;

    #[Virtual]
    public bool $sorted = false;

    public function hasPermission(string $name): bool
    {
        // Yeah, not elegant, but makes it faster when we have many permissions.
        if ($this->sorted === false) {
            foreach ($this->permissions as $permission) {
                $this->permissions[$permission->name] = $permission;
            }
            $this->sorted = true;
        }

        return $this->permissions[$name]->has();
    }
}
