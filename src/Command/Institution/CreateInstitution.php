<?php

declare(strict_types=1);

namespace Tokei\Command\Institution;

use Tokei\Command\IsResettable;
use Tokei\Command\Resettable;

final class CreateInstitution implements Resettable
{
    use IsResettable;

    public function __construct(
        public string $name = '',
        public string $educator = '',
        public string $email = '',
        public string $phone = '',
        public string $seal = '',
        public string $type = '',
        public string $postalCode = '',
    ) {}
}
