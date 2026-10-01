<?php

declare(strict_types=1);

namespace Tokei\Extension\Validation\Rules;

use Attribute;
use Tempest\Validation\Rule;
use Tokei\Model\Event\Template;

#[Attribute]
final class IsNotExistingEventTemplate implements Rule
{

    public function isValid(mixed $value): bool
    {
        return Template::select()->where('name LIKE ?', $value)->first() === null;
    }
}
