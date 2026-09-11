<?php

declare(strict_types=1);

namespace Tokei\Tool\Model;

use InvalidArgumentException;
use Tokei\Model\Routes;

final class RouteCollection
{
    protected const string VIEW = 'view';
    protected const string LIST = 'list';
    protected const string CREATE = 'create';
    protected const string UPDATE = 'update';
    protected const string DELETE = 'delete';

    /**
     * @var array<string, array<string, ?Route>>
     */
    protected array $routes = [];

    public function __construct(
        protected(set) string $className,
        Routes ...$routes
    ) {
        $this->routes = [
            RouteContext::Admin->value => [],
            RouteContext::Api->value => [],
            RouteContext::Public->value => []
        ];

        $this->prepare(...$routes);
    }

    protected function prepare(Routes ...$routes): void
    {
        foreach ($routes as $route) {
            $context = RouteContext::fromPrefix($route->prefix);
            $prefix = str_starts_with($route->prefix, '/') ? $route->prefix : '/' . $route->prefix;
            $prefix = str_ends_with($prefix, '/') ? $prefix : $prefix . '/';

            $this->routes[$context->value][self::VIEW] = ($route->viewUri !== '') ? new Route($prefix . ltrim($route->viewUri, '/')) : null;
            $this->routes[$context->value][self::LIST] = ($route->listUri !== '') ? new Route($prefix . ltrim($route->listUri, '/')) : null;
            $this->routes[$context->value][self::CREATE] = ($route->createUri !== '') ? new Route($prefix . ltrim($route->createUri, '/')) : null;
            $this->routes[$context->value][self::UPDATE] = ($route->updateUri !== '') ? new Route($prefix . ltrim($route->updateUri, '/')) : null;
            $this->routes[$context->value][self::DELETE] = ($route->deleteUri !== '') ? new Route($prefix . ltrim($route->deleteUri, '/')) : null;
        }
    }

    public function getUri(string|object $model, string|RouteContext $context, string $type = 'view'): string
    {
        $className = (is_object($model)) ? get_class($model) : $model;
        $type = strtolower($type);
        $context = (is_string($context)) ? RouteContext::fromParameter($context) : $context;

        if ($className !== $this->className) {
            throw new InvalidArgumentException('... Who calls this with the wrong molde or class name!'); // funny, realy funn, but CollectionRegistry checks class/model sooooooooooo thats more for direct calling.
        }

        $route = $this->routes[$context->value][$type] ?? null;

        if ($route === null) {
            throw new InvalidArgumentException('Route not found.');
        }

        if ($type === self::LIST || $type === self::CREATE) {
            return $route->prepare(null);
        }

        return $route->prepare($model);
    }
}