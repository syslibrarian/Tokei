<?php

declare(strict_types=1);

namespace Tokei\Component\Access;

use Tempest\Auth\Exceptions\AccessWasDenied;
use Tokei\Model\User\User;

interface AccessControl
{
    public User $user { get; }

    public function hasPermission(?string $name): bool;

    /**
     * @param object|class-string $model
     * @param AccessContext|null $context
     * @return void
     * @throws AccessWasDenied
     */
    public function checkModel(object|string $model, ?AccessContext $context = null): void;

    /**
     * @param string $name
     * @return void
     * @throws AccessWasDenied
     */
    public function checkPermission(string $name): void;

    /**
     * @param object|class-string $model
     * @param AccessContext|null $context
     * @return bool
     */
    public function hasModelPermission(object|string $model, ?AccessContext $context = null): bool;
}
