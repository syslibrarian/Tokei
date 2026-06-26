<?php

declare(strict_types=1);

namespace Tokei\Tool\Installer;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class DatabaseData
{
    public function __construct(
        public string $modelClass,
        public InstallType $type = InstallType::INSTALL,
        public string $sourceFile = '',
    ) {}
}
