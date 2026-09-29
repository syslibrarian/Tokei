<?php

declare(strict_types=1);

namespace Tokei\Component\Access;


use Tempest\Auth\Authentication\Authenticator;
use Tempest\Container\Singleton;
use Tokei\Model\User\User;

#[Singleton]
final class HttpAccessControl implements AccessControl
{
    use IsAccessControl;

    public function __construct(
        Authenticator $authenticator,
    ) {
        $this->setUser($authenticator->current());
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
}
