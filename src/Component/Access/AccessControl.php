<?php

declare(strict_types=1);

namespace Tokei\Component\Access;

use Tempest\Auth\AccessControl\AccessDecision;
use Tempest\Auth\Authentication\Authenticator;
use Tempest\Auth\Exceptions\AccessWasDenied;
use Tempest\Container\Singleton;
use Tokei\Model\User\User;

#[Singleton]
final class AccessControl
{
    protected(set) User $user;

    public function __construct(
        protected(set) Authenticator $authenticator,
    ) {
        $this->setUser();
    }

    private function setUser(): void
    {
        $user = $this->authenticator->current();
        if ($user instanceof User) {
            $this->user = User::select()->with('role', 'role.permissions')->where('user.id = ?', $user->id->value)->first();
        }
    }

    public function hasPermission(?string $name): bool
    {
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

                return $permission->check($this, (is_object($model) ? $model : null));
            }

            return false;
        }

        return false;
    }

    public function canCreate(string|object $modelClass): bool
    {
        return $this->hasModelPermission($modelClass, AccessContext::CREATE);
    }

    public function canUpdate(string|object $modelClass): bool
    {
        return $this->hasModelPermission($modelClass, AccessContext::UPDATE);
    }

    public function canDelete(string|object $modelClass): bool
    {
        return $this->hasModelPermission($modelClass, AccessContext::DELETE);
    }

    public function isSelf(User $user): bool
    {
        return $user->id === $this->user->id;
    }
}
