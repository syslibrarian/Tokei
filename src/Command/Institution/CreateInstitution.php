<?php

declare(strict_types=1);

namespace Tokei\Command\Institution;

use Tokei\Command\Command;

final class CreateInstitution implements Command
{
    public function __construct(
        public string $name,
        public string $educator,
        public string $email,
        public string $phone,
        public string $seal,
        public string $type,
    ) {}
}
