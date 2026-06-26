<?php
declare(strict_types=1);

namespace Tokei\Command\User;

use Tokei\Command\Command;

final class CreateRole implements Command
{
    public function __construct(
        public string $name,
        public array $permissions,
    ) {}
}
