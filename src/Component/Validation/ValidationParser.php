<?php

declare(strict_types=1);

namespace Tokei\Component\Validation;

use Tempest\Container\Singleton;
use Tempest\Validation\Exceptions\ValidationFailed;

use function Tempest\Support\Arr\last;
use function Tempest\Support\Str\to_snake_case;

#[Singleton]
final class ValidationParser
{
    public array $parsedErrors = [];

    public function parse(ValidationFailed $validationFailed): void
    {
        $errorFields = $validationFailed->failingRules;
        foreach ($errorFields as $field => $errors) {
            $this->parsedErrors[$field] = [];
            foreach ($errors as $error) {
                $this->parsedErrors[$field][] = $this->messagetoError($error);
            }
        }
    }

    protected function messageToError(object $error): string
    {
        $errorRule = to_snake_case(last(explode('\\', get_class($error->rule))));
        return 'tokei.error.' . $errorRule;
    }
}
