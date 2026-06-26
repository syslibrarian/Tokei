<?php

declare(strict_types=1);

namespace Tokei\Extension\Validation\Rules;

use Tempest\Validation\Rule;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class IsSamePassword implements Rule
{
    public function __construct(
        #[\SensitiveParameter]
        protected(set) string $pr,
    ) {}

    public function isValid(#[\SensitiveParameter] mixed $value): bool
    {
        return hash_equals($value, $this->pr);
    }
}
