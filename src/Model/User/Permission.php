<?php

declare(strict_types=1);

namespace Tokei\Model\User;

use Tempest\Database\IsDatabaseModel;
use Tempest\Database\Table;

#[Table(name: 'user_permission')]
final class Permission
{
    use IsDatabaseModel;

    public string $name;
    public int $value;
    public int $role_id;

    public function has(): bool
    {
        return $this->value === 1;
    }
}
