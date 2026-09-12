<?php

declare(strict_types=1);

namespace Tokei\str {
    function trim(string $str): string
    {
        $boundaryCharacters = '\p{Cc}\p{Zs}\p{Zl}\p{Zp}\s\x{202E}\x{200B}';
        $fullStringCharacters = $boundaryCharacters . '\p{Cf}\p{Z}\x{2800}\x{3164}\x{FFA0}\x{1D159}\x{1D173}-\x{1D17A}';

        $trimmed = \preg_replace("/^[{$boundaryCharacters}]+/u", '', $str);
        if ($trimmed === null) {
            return $str;
        }

        $trimmed = \preg_replace("/[{$boundaryCharacters}]+$/u", '', $trimmed);
        if ($trimmed === null) {
            return $str;
        }

        if (\preg_match("/^[{$fullStringCharacters}]+$/u", $trimmed)) {
            return '';
        }

        return $trimmed;
    }
}

namespace Tokei\misc {
    use InvalidArgumentException;
    use Tokei\Tool\Model\RouteCollectionRegistry;
    use Tokei\Tool\Model\RouteContext;

    use function Tempest\Container\get;

    function getUri(string|object $object, string|RouteContext $context = 'public', string $type = 'view', string $appendUri = '', mixed ...$args): string
    {
        return rtrim(get(RouteCollectionRegistry::class)->getUri($object, $context, $type), '/') . buildUri($appendUri, ...$args);
    }

    function buildUri(string $uri, mixed ...$args): string
    {
        $uri = str_starts_with($uri, '/') ? $uri : '/' . $uri;

        if (str_contains($uri, '{')) {
            $parameters = [];

            preg_match_all('#{([_a-zA-Z]+[_a-zA-Z0-9]*)}#', $uri, $parameters, PREG_SET_ORDER);
            foreach ($parameters as [$placeholder, $name]) {
                $value = $args[$name] ?? null;

                if ($value === null) {
                    throw new InvalidArgumentException(sprintf('Missing parameter "%s"', $name));
                }

                unset($args[$name]);
                $uri = str_replace($placeholder, (string) $value, $uri);
            }
        }

        if (count($args) > 0) {
            $uri .= (str_contains($uri, '?') ? '&' : '?') . http_build_query($args);
        }

        return $uri;
    }
}

