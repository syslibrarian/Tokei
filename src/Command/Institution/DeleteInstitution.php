<?php

declare(strict_types=1);

namespace Tokei\Command\Institution;

use Tokei\Command\Command;
use Tokei\Model\Institution\Institution;

class DeleteInstitution implements Command
{
    public function __construct(
        public Institution $model
    ) {}
}
