<?php

declare(strict_types=1);

namespace Tokei\Extension\Validation\Rules;

use Tokei\Model\User\Role;
use Tempest\Validation\Rule;

#[\Attribute]
final class IsNotExistingRole implements Rule
{
    public function isValid(mixed $value): bool
    {
        if (empty($value)) {
            return true;
        }

        $role = Role::select()
            ->where('name = ?', $value)
            ->first();

        return $role === null;
    }
}
