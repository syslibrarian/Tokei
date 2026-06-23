<?php

declare(strict_types=1);

namespace Tokei\Command\Handler;

use Tempest\CommandBus\CommandHandler;
use Tempest\Cryptography\Password\PasswordHasher;
use Tempest\Validation\Exceptions\ValidationFailed;
use Tempest\Validation\Validator;
use Tokei\Command\IsHandler;
use Tokei\Command\User\CreateRole;
use Tokei\Command\User\CreateUser;
use Tokei\Command\User\DeleteRole;
use Tokei\Command\User\DeleteUser;
use Tokei\Command\User\UpdateRole;
use Tokei\Command\User\UpdateUser;
use Tokei\Extension\Validation\Rules\IsSamePassword;
use Tokei\Extension\Validation\Rules\IsSecurePassword;
use Tokei\Model\User\Permission;
use Tokei\Model\User\Role;
use Tokei\Model\User\RoleHelper;
use Tokei\Model\User\User;

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

    private Validator $validator {
        get {
            return get(Validator::class);
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
    public function create(CreateUser $createUser): void
    {
        try {
            $this->transaction->begin();
            $this->checkPassword($createUser->password, $createUser->password_repeat);

            $passwordHashed = $this->hasher->hash($createUser->password);
            $user = User::create(
                username: $createUser->username,
                password: $passwordHashed,
                email: $createUser->email,
                name: $createUser->name,
                surname: $createUser->surname,
                role_id: $createUser->role_id,
            );

            if ($createUser->seal !== 'x') {
                $user->update(
                    seal: $createUser->seal,
                );
            }
        } catch (ValidationFailed $e) {
            $this->transaction->rollback();
            $this->response->set($createUser, $e);
            return;
        }

        $this->transaction->commit();
        $this->response->set($createUser, $user);
    }

    #[CommandHandler]
    public function update(UpdateUser $updateUser): void
    {
        try {
            $this->transaction->begin();

            $changed = [
                'name' => $updateUser->name,
                'surname' => $updateUser->surname,
                'seal' => $updateUser->seal,
                'role_id' => $updateUser->role_id,
            ];
            if ($updateUser->username !== $updateUser->model->username) {
                $changed['username'] = $updateUser->model->username;
            }

            if ($updateUser->email !== $updateUser->model->email) {
                $changed['email'] = $updateUser->model->email;
            }

            if ($updateUser->change_password) {
                $this->checkPassword($updateUser->password, $updateUser->password_repeat);
                $changed['password'] = $this->hasher->hash($updateUser->password);
            }

            $updateUser->model->update(...$changed);
        } catch (ValidationFailed $e) {
            $this->transaction->rollback();
            $this->response->set($updateUser, $e);
            return;
        }

        $this->transaction->commit();
        $this->response->set($updateUser, true);
    }

    #[CommandHandler]
    public function delete(DeleteUser $deleteUser): void
    {
        $deleteUser->model->delete();
    }

    private function checkPassword(string $password, string $password_repeat): void
    {
        $failingRules = $this->validator->validateValues(
            ['password' => ['password' => $password, 'password_repeat' => $password_repeat]],
            ['password' => [new IsSamePassword(), new IsSecurePassword()]],
        );

        if (count($failingRules)) {
            throw new ValidationFailed($failingRules);
        }
    }
}
