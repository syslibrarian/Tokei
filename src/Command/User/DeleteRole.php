<?php
declare(strict_types=1);

namespace Tokei\Command\User;

use Tokei\Command\Command;
use Tokei\Model\User\Role;

final class DeleteRole implements Command
{
    public function __construct(
        public Role $role,
    ) {}
}
