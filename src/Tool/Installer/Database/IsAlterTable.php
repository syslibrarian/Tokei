<?php

declare(strict_types=1);

namespace Tokei\Tool\Installer\Database;

use Tempest\Database\Query;
use Tempest\Database\QueryStatements\AlterTableStatement;
use Tokei\Tool\Installer\DatabaseTable;

trait IsAlterTable
{
    protected AlterTableStatement $statement;

    public function __construct()
    {
        $this->initStatement();
    }

    public function execute(): void
    {
        $query = new Query($this->statement);
        $query->execute();
    }

    protected function initStatement(): void
    {
        $reflectionClass = new \ReflectionClass($this);
        $attributes = $reflectionClass->getAttributes(DatabaseTable::class);

        if (empty($attributes)) {
            throw new \RuntimeException(static::class . ' must have ' . DatabaseTable::class . ' attribut');
        }

        /** @var DatabaseTable $databaseSetup */
        $databaseSetup = $attributes[0]->newInstance();
        $this->statement = new AlterTableStatement($databaseSetup->modelClass);

        $this->setFields();
    }

    abstract protected function setFields(): void;
}
