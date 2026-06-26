<?php

declare(strict_types=1);

namespace Tokei\Extension\Validation\Rules;

use Tempest\Validation\Rule;
use Tokei\Model\User\User;

#[\Attribute]
final class IsExistingUsername implements Rule
{
    public function isValid(mixed $value): bool
    {
        $user = User::select()->where('username LIKE ?', $value)->first();

        return $user === null;
    }
}
