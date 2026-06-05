<?php

declare(strict_types=1);

namespace Tokei\Component\Validation;

use Tempest\Container\Singleton;
use Tempest\Validation\Exceptions\ValidationFailed;

#[Singleton]
final class ValidationParser
{
    public array $parsedErrors = [];

    public function parse(ValidationFailed $validationFailed): void
    {
        $errorFields = $validationFailed->failingRules;
        foreach ($errorFields as $field => $errors) {
            $this->parsedErrors[$field] = '';
            foreach ($errors as $error) {
                $this->parsedErrors[$field] .= (is_object($error->rule) ? \get_class($error->rule) : '') . "\n";
            }
        }
    }
}
