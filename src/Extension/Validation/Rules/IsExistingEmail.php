<?php

declare(strict_types=1);

namespace Tokei\Extension\Validation\Rules;

use Tempest\Validation\Rule;
use Tokei\Model\User\User;

#[\Attribute]
final class IsExistingEmail implements Rule
{
    public function isValid(mixed $value): bool
    {
        $user = User::select()->where('email LIKE ?', $value)->first();

        return $user === null;
    }
}
