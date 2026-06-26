<?php

declare(strict_types=1);

namespace Tokei\Extension\Validation\Rules;

use Tempest\Validation\Rule;
use Tokei\Model\User\Role;

#[\Attribute]
final class IsNotExistingRole implements Rule
{
    public function isValid(mixed $value): bool
    {
        $role = Role::select()
            ->where('name = ?', $value)
            ->first();

        return $role === null;
    }
}
