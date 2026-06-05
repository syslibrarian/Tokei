<?php

declare(strict_types=1);

namespace Tokei\Event\User;

use Tokei\Tool\User\Permissions;

final class PermissionsPrepare
{
    public function __construct(
        public Permissions $permissions,
    ) {}
}
