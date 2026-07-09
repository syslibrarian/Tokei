<?php

declare(strict_types=1);

namespace Tokei\Tool\Installer\Database;

use Tokei\Model\Institution\Institution;
use Tokei\Tool\Installer\DatabaseTable;
use Tokei\Tool\Installer\InstallType;

#[DatabaseTable(modelClass: Institution::class, type: InstallType::INSTALL)]
final class InstitutionCreateTable implements DatabaseCommand
{
    use IsCreateTable;

    protected function setFields(): void
    {
        $this->statement
            ->primary()
            ->varchar('name')
            ->varchar('educator')
            ->varchar('type', length: 10)
            ->varchar('email', default: '')
            ->varchar('phone', default: '')
            ->varchar('seal', 4)
            ->integer('created')
            ->integer('modified', default: 0)
            ->integer('last_event', default: 0)
            ->varchar('postal_code', length: 5)
            ->index('seal', 'type');
    }
}
