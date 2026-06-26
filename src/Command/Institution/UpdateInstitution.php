<?php

declare(strict_types=1);

namespace Tokei\Command\Institution;

use Tokei\Command\Command;
use Tokei\Model\Institution\Institution;

final class UpdateInstitution implements Command
{
    public function __construct(
        public Institution $model,
        public string $name,
        public string $educator,
        public string $email,
        public string $phone,
        public string $seal,
        public string $type,
    ) {}
}
