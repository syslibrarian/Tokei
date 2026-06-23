<?php

declare(strict_types=1);

namespace Tokei\Extension\Validation\Rules;

use Tempest\Validation\Rule;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class IsSamePassword implements Rule
{
    public function isValid(mixed $value): bool
    {
        return $value['password'] === $value['password_repeat'];
    }
}
