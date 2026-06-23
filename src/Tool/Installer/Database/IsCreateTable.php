<?php

declare(strict_types=1);

namespace Tokei\Tool\Installer\Database;

use Tempest\Database\Query;
use Tempest\Database\QueryStatements\CreateTableStatement;
use Tempest\Reflection\ClassReflector;
use Tokei\Tool\Installer\DatabaseTable;

trait IsCreateTable
{
    protected CreateTableStatement $statement;

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
        $reflector = new ClassReflector($this);
        $databaseSetup = $reflector->getAttribute(DatabaseTable::class);

        if (empty($databaseSetup)) {
            throw new \RuntimeException(static::class . ' must have ' . DatabaseTable::class . ' attribute');
        }

        $this->statement = CreateTableStatement::forModel($databaseSetup->modelClass);

        $this->setFields();
    }

    abstract protected function setFields(): void;
}
