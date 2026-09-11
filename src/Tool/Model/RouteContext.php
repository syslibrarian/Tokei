<?php

declare(strict_types=1);

namespace Tokei\Tool\Model;

enum RouteContext: string
{
    case Admin = 'adm';
    case Api = 'api';
    case Public = 'Public';

    public static function fromPrefix(string $prefix): self
    {
        $prefix = ltrim($prefix, '/');
        $segment = explode('/', ltrim($prefix, '/'))[0] ?? '';

        return self::fromParameter($segment);
    }

    public static function fromParameter(string $prefix): self
    {
        return match (strtolower($prefix)) {
            'adm' => self::Admin,
            'api' => self::Api,
            default => self::Public,
        };
    }
}
