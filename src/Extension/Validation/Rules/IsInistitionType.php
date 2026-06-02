<?php

declare(strict_types=1);

namespace Tokei\Extension\Validation\Rules;

use Tempest\Validation\Rule;
use Tokei\Model\Institution\Type;

#[\Attribute]
final class IsInistitionType implements Rule
{

    public function isValid(mixed $value): bool
    {
        return Type::checkType($value);
    }
}