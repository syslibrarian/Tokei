<?php

declare(strict_types=1);

namespace Tokei\Tool\Installer;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class DatabaseTable
{
    public function __construct(
        /** @param class-string $modelClass */
        public string $modelClass,
        public InstallType $type = InstallType::INSTALL,
        /** @param class-string $after */
        public string $after = '',
        public string $fromVersion = '',
    ) {}
}
