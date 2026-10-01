<?php

declare(strict_types=1);

namespace Tokei\Tool\Installer\Database;

use Tokei\Model\Event\Template;
use Tokei\Tool\Installer\DatabaseTable;
use Tokei\Tool\Installer\InstallType;

#[DatabaseTable(modelClass: Template::class, type: InstallType::INSTALL)]
final class EventTemplateCreateTable implements DatabaseCommand
{
    use IsCreateTable;

    protected function setFields(): void
    {
        $this->statement
            ->primary()
            ->varchar('name', default: '')
            ->varchar('description', default: '')
            ->integer('length');
    }
}

