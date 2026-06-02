<?php

declare(strict_types=1);

namespace Tokei\Extension\Validation\Rules;

use Tempest\Validation\Rule;
use Tokei\Model\Location\LocationHelper;

#[\Attribute]
class IsExistingSeal implements Rule
{

    public function isValid(mixed $value): bool
    {
        return LocationHelper::isExistingSeal($value);
    }
}
