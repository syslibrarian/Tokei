<?php

declare(strict_types=1);

namespace Tokei\Component\Access;

use Tokei\Model\User\User;
use Tempest\Auth\AccessControl\AccessDecision;
use Tempest\Auth\Authentication\Authenticator;
use Tempest\Auth\Exceptions\AccessWasDenied;

final class AccessControl
{
    public function __construct(
        protected(set) Authenticator $authenticator,
    ) {}

    public function hasPermission(string $name): bool
    {
        $user = $this->authenticator->current();

        if ($user instanceof User) {
            return $user->role->hasPermission($name);
        }

        return false;
    }

    public function checkPermission(string $name): void
    {
        if (! $this->hasPermission($name)) {
            throw new AccessWasDenied(AccessDecision::Denied($name));
        }
    }
}
