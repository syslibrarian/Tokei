<?php

declare(strict_types=1);

namespace Tokei\Tool\Model;

use InvalidArgumentException;
use Tempest\Database\PrimaryKey;

final class Route
{
    public function __construct(protected(set) string $uri)
    {}

    public function prepare(?object $model): string
    {
        if ($model === null || !str_contains($this->uri, '{')) {
            return $this->uri;
        }

        $parameters = [];
        $uri = $this->uri;
        preg_match_all('#{([_a-zA-Z]+[_a-zA-Z0-9]*)}#', $this->uri, $parameters, PREG_SET_ORDER);
        foreach ($parameters as $parameter) {
            $placeholder = $parameter[0];
            $name = $parameter[1];
            $value = $model?->{$name};

            if ($value === null) {
                throw new InvalidArgumentException(sprintf('Property $%s on %s is null.', $model::class, $name));
            }

            $uri = str_replace($placeholder, (string) $value, $uri);
        }

        return $uri;
    }
}
