<?php

declare(strict_types=1);

namespace Tokei\Extension\Validation\Rules;

use Tempest\Validation\Rule;
use Tokei\Model\Location\LocationHelper;

#[\Attribute]
final class IsExistingSeal implements Rule
{
    public function isValid(mixed $value): bool
    {
        return $value === '' || LocationHelper::isExistingSeal($value);
    }
}
