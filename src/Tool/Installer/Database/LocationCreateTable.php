<?php

declare(strict_types = 1);

namespace Tokei\Tool\Installer\Database;

use Tokei\Model\Location\Location;
use Tokei\Tool\Installer\DatabaseTable;
use Tokei\Tool\Installer\InstallType;

#[DatabaseTable(modelClass: Location::class, type: InstallType::INSTALL)]
final class LocationCreateTable implements DatabaseCommand
{
    use IsCreateTable;

    protected function setFields(): void
    {
        $this->statement
            ->primary()
            ->varchar('name')
            ->varchar('seal', 4)
            ->varchar('street')
            ->varchar('city')
            ->varchar('zip_code', 5)
            ->float('fte')
            ->float('fte_consumed')
            ->float('area')
            ->index('seal');
    }
}
