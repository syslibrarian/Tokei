<?php

declare(strict_types=1);

namespace Tokei\Component\Access;

use Tempest\Auth\Authentication\Authenticator;
use Tempest\Discovery\SkipDiscovery;
use Tempest\Http\Request;
use Tempest\Http\Response;
use Tempest\Http\Responses\Redirect;
use Tempest\Router\HttpMiddleware;
use Tempest\Router\HttpMiddlewareCallable;

#[SkipDiscovery]
final class IsAuthenticated implements HttpMiddleware
{
    public function __construct(
        protected Authenticator $authenticator,
    ) {}

    public function __invoke(Request $request, HttpMiddlewareCallable $next): Response
    {
        if ($this->authenticator->current() === null) {
            return new Redirect('/login');
        }

        return $next($request);
    }
}
