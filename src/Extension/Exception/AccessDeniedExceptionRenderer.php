<?php

declare(strict_types=1);

namespace Tokei\Extension\Exception;

use Tempest\Auth\Exceptions\AccessWasDenied;
use Tempest\Http\Request;
use Tempest\Http\Response;
use Tempest\Http\Responses\Forbidden;
use Tempest\Router\Exceptions\ExceptionRenderer;
use Throwable;

use function Tempest\View\view;

final class AccessDeniedExceptionRenderer implements ExceptionRenderer
{
    public function canRender(Throwable $throwable, Request $request): bool
    {
        return $throwable instanceof AccessWasDenied;
    }

    public function render(Throwable $throwable): Response
    {
        return new Forbidden()->setBody(
            view('accessDenied.tpl', name: $throwable->getMessage()),
        );
    }
}
