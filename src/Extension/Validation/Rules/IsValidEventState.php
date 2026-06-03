<?php

declare(strict_types = 1);

namespace Tokei\Extension\Validation\Rules;

use Tempest\Validation\Rule;
use Tokei\Model\Event\EventHelper;

#[\Attribute]
final class IsValidEventState implements Rule
{

    public function isValid(mixed $value): bool
    {
        return array_key_exists((int) $value, EventHelper::STATE);
    }
}
