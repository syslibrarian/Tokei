<?php

declare(strict_types=1);

namespace Tokei\Command\Handler;

use Tokei\Command\IsHandler;
use Tokei\Command\User\CreateRole;
use Tokei\Command\User\CreateUser;
use Tokei\Command\User\DeleteRole;
use Tokei\Command\User\DeleteUser;
use Tokei\Command\User\UpdateRole;
use Tokei\Command\User\UpdateUser;
use Tokei\Model\User\Permission;
use Tokei\Model\User\Role;
use Tokei\Model\User\RoleHelper;
use Tempest\CommandBus\CommandHandler;
use Tempest\Cryptography\Password\PasswordHasher;
use Tempest\Validation\Exceptions\ValidationFailed;

use function Tempest\Container\get;
use function Tempest\Support\arr\each;

final class UserHandler
{
    use IsHandler;

    private PasswordHasher $hasher {
        get {
            return get(PasswordHasher::class);
        }
    }

    #[CommandHandler]
    public function createRole(CreateRole $createRole): void
    {
        try {
            $this->transaction->begin();

            $role = Role::create(name: $createRole->name);

            each($createRole->permissions, static function ($value, $key) use ($role) {
                Permission::create(role_id: $role->id->value, name: $key, value: $value);
            });
        } catch (ValidationFailed $e) {
            $this->response->set($createRole, $e);
            $this->transaction->rollback();

            return;
        }

        $this->transaction->commit();
        $this->response->set($createRole, $role);
    }

    #[CommandHandler]
    public function updateRole(UpdateRole $updateRole): void
    {
        try {
            $this->transaction->begin();
            if ($updateRole->name !== $updateRole->model->name) {
                $updateRole->model->update(name: $updateRole->name);
            }

            each($updateRole->permissions, static function ($value, $key) use ($updateRole) {
                Permission::updateOrCreate(
                    ['role_id' => $updateRole->model->id->value, 'name' => $key],
                    ['value' => $value],
                );
            });
        } catch (ValidationFailed $e) {
            $this->response->set($updateRole, $e);
            $this->transaction->rollback();
            return;
        }

        $this->transaction->commit();
        $this->response->set($updateRole, true);
    }

    #[CommandHandler]
    public function deleteRole(DeleteRole $deleteRole): void
    {
        if (! RoleHelper::hasUsers($deleteRole->role)) {
            $deleteRole->role->delete();
            $this->response->set($deleteRole, true);
            return;
        }

        $this->response->set($deleteRole, false);
    }

    #[CommandHandler]
    public function createUser(CreateUser $createUser): void {}

    #[CommandHandler]
    public function updateUser(UpdateUser $updateUser): void {}

    #[CommandHandler]
    public function deleteUser(DeleteUser $deleteUser): void {}
}
