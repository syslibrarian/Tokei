<?php

declare(strict_types=1);

namespace Tokei\Component\Access;

use Tempest\Auth\AccessControl\AccessDecision;
use Tempest\Auth\Authentication\Authenticator;
use Tempest\Auth\Exceptions\AccessWasDenied;
use Tokei\Model\User\User;

final class AccessControl
{
    protected User $user;
    public function __construct(
        protected(set) Authenticator $authenticator,
    ) {
        $this->setUser();
    }

    protected function setUser(): void
    {
        $user = $this->authenticator->current();
        if ($user instanceof User) {
            $this->user = $user;
        } //else {
            //throw new AccessWasDenied(); // now valid user object from authenticator
        //}
    }

    public function hasPermission(string $name): bool
    {
        $user = $this->authenticator->current();

        if ($user instanceof User) {
            return $user->role->hasPermission($name);
        }

        return true; // current state
    }

    public function checkModel(object|string $model, ?AccessContext $context = null): void
    {
        $permissionClass = AccessContext::getClass($context, $model);

        // we have no permission, so no check
        if ($permissionClass === null) {
            return;
        }
    }

    public function checkPermission(string $name): void
    {
        if (! $this->hasPermission($name)) {
            throw new AccessWasDenied(AccessDecision::Denied($name));
        }
    }

    public function canCreate(string|object $modelClass): bool
    {
        return true;
    }

    public function canUpdate(string|object $modelClass): bool
    {
        return true;
    }

    public function canDelete(string|object $modelClass): bool
    {
        return true;
    }
}
