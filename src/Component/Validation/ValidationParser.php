<?php

declare(strict_types=1);

namespace Tokei\Component\Validation;

use Tempest\Container\Singleton;
use Tempest\Validation\Exceptions\ValidationFailed;

#[Singleton]
class ValidationParser
{
    public array $parsedErrors = [];

    public function parse(ValidationFailed $validationFailed): void
    {
        $errorFields = $validationFailed->failingRules;
        foreach ($errorFields as $field => $errors) {
            $this->parsedErrors[$field] = '';
            foreach ($errors as $error) {
                $this->parsedErrors[$field] .= \get_class($error) . "\n";
            }
        }
    }
}
