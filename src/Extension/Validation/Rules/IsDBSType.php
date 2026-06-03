<?php

declare(strict_types = 1);

namespace Tokei\Extension\Validation\Rules;

use Tempest\Validation\Rule;
use Tokei\Model\Event\DBSSection;

#[\Attribute]
final class IsDBSType implements Rule
{

    public function isValid(mixed $value): bool
    {
        return DBSSection::exists($value);
    }
}
