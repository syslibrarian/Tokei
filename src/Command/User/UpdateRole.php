<?php
declare(strict_types=1);

namespace Tokei\Command\User;

use Tokei\Command\Command;
use Tokei\Model\User\Role;

final class UpdateRole implements Command
{
    public function __construct(
        public string $name,
        public array $permissions,
        public Role $model,
    ) {}
}
