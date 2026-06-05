<?php

declare(strict_types=1);

namespace Tokei\Extension\Validation\Rules;

use Tokei\Model\User\User;
use Tempest\Validation\Rule;

#[\Attribute]
final class IsNotExistingEmail implements Rule
{
    public function isValid(mixed $value): bool
    {
        $user = User::select()->where('email = ?', $value)->first();

        return $user !== null;
    }
}
