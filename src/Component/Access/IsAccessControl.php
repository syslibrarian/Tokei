<?php

declare(strict_types=1);

namespace Tokei\Component\Access;

use Tempest\Auth\AccessControl\AccessDecision;
use Tempest\Auth\Authentication\Authenticator;
use Tempest\Auth\Exceptions\AccessWasDenied;
use Tempest\Database\PrimaryKey;
use Tokei\Model\User\User;

trait IsAccessControl
{
    protected(set) User $user;

    protected function getGuest(): User
    {
        $guest = new User();
        $guest->username = 'Guest';
        $guest->name = '';
        $guest->surname = '';
        $guest->email = '';
        $guest->seal = 'all';
        $guest->id = new PrimaryKey(0);
        $guest->role = null;

        return $guest;
    }

    protected function setUser(?User $user = null): void
    {
        $this->user = ($user !== null)
            ? User::select()->with('role', 'role.permissions')->where('user.id = ?', $user->id->value)->first()
            : $this->getGuest();
    }

    public function hasPermission(?string $name): bool
    {
        if ($this->user->role === null) {
            return false;
        }

        if ($name === '' || $name === null) {
            return true;
        }

        return $this->user->role->hasPermission($name);
    }

    public function checkModel(object|string $model, ?AccessContext $context = null): void
    {
        if (! $this->hasModelPermission($model, $context)) {
            throw new AccessWasDenied(AccessDecision::Denied());
        }
    }

    public function checkPermission(string $name): void
    {
        if (! $this->hasPermission($name)) {
            throw new AccessWasDenied(AccessDecision::Denied($name));
        }
    }

    public function hasModelPermission(object|string $model, ?AccessContext $context = null): bool
    {
        $permissionClass = AccessContext::getClass($model, $context);

        if ((is_string($model) && class_exists($model)) || is_object($model)) {
            $reflection = new \ReflectionClass($model);
            $attribute = $reflection->getAttributes($permissionClass)[0] ?? null;

            if ($attribute !== null) {
                /** @var Permission $permission */
                $permission = $attribute->newInstance();

                return $permission->check($this, is_object($model) ? $model : null);
            }

            return false;
        }

        return false;
    }
}
