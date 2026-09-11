<?php

declare(strict_types=1);

namespace Tokei\Tool\Model;

use RuntimeException;
use Tempest\Container\Singleton;

#[Singleton]
final class RouteCollectionRegistry {
    /**
     * @var array<string, RouteCollection>
     */
    protected array $modelRoutes = [];

    public function __construct()
    {
        // only for clean state.
        $this->modelRoutes = [];
    }

    public function register(RouteCollection $collection): self
    {
        if (isset($this->modelRoutes[$collection->className])) {
            throw new RuntimeException(sprintf('Route collection for "%s" is already registered.', $collection->className));
        }

        $this->modelRoutes[$collection->className] = $collection;

        return $this;
    }

    public function getUri(string|object $model, string|RouteContext $context, string $type): string
    {
        $className = is_object($model) ? get_class($model) : $model;

        if (!isset($this->modelRoutes[$className])) {
            throw new RuntimeException(sprintf('Route collection for "%s" is not registered.', $className));
        }

        return $this->modelRoutes[$className]->getUri($model, $context, $type);
    }
}
