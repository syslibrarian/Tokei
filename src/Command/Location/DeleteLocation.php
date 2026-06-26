<?php

declare(strict_types=1);

namespace Tokei\Command\Location;

use Tokei\Command\Command;
use Tokei\Model\Location\Location;

final class DeleteLocation implements Command
{
    public function __construct(
        public Location $model,
    ) {}
}
