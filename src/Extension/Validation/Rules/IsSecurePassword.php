<?php

declare(strict_types=1);

namespace Tokei\Extension\Validation\Rules;

use Tempest\Validation\Rule;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class IsSecurePassword implements Rule
{
    public function isValid(mixed $value): bool
    {
        $password = is_array($value) ? $value['password'] ?? '' : (string) $value;

        if (mb_Strlen($password) < 12) {
            return false;
        }

        if (! preg_match('#\p{Ll}#u', $password)) {
            return false;
        }

        if (! preg_match('#\p{Lu}#u', $password)) {
            return false;
        }

        if (! preg_match('#\p{Nd}#u', $password)) {
            return false;
        }

        if (! preg_match('#\p{S}|\p{P}#u', $password)) {
            return false;
        }

        return true;
    }
}
