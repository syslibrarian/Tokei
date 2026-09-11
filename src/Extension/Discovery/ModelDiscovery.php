<?php

declare(strict_types=1);

namespace Tokei\Extension\Discovery;

use Tokei\Model\Identifier;
use Tokei\Model\Routes;
use Tokei\Tool\Model\RouteCollection;
use Tokei\Tool\Model\RouteCollectionRegistry;
use Tokei\Tool\Model\Type;
use Tokei\Tool\Model\TypeRegistry;
use Tempest\Discovery\Discovery;
use Tempest\Discovery\DiscoveryLocation;
use Tempest\Discovery\IsDiscovery;
use Tempest\Reflection\ClassReflector;

final class ModelDiscovery implements Discovery
{
    use IsDiscovery;

    public function __construct(
        protected(set) TypeRegistry $typeRegistry,
        protected(set) RouteCollectionRegistry $routeCollectionRegistry,
    ) {}


    public function discover(DiscoveryLocation $location, ClassReflector $class): void
    {

        $data = [
            'type' => $this->handleIdentifier($class),
            'routeCollection' => $this->handleRoutes($class),
        ];

        $this->discoveryItems->add($location, $data);
    }

    public function apply(): void
    {
        foreach ($this->discoveryItems as $location => $data) {
            if (isset($data['routeCollection'])) {
                $this->routeCollectionRegistry->register($data['routeCollection']);
            }

            if (isset($data['type'])) {
                $this->typeRegistry->register($data['type']);
            }
        }
    }

    protected function handleRoutes(ClassReflector $class): ?RouteCollection
    {
        if ($class->hasAttribute(Routes::class)) {
            $className = $class->getName();
            $rawRoutes = $class->getAttributes(Routes::class);
            return new RouteCollection($className, ...$rawRoutes);
        }

        return null;
    }

    protected function handleIdentifier(ClassReflector $class): ?Type
    {
        if ($class->hasAttribute(Identifier::class)) {
            $identifier = $class->getAttribute(Identifier::class);

            return new Type($identifier->name, $class->getName(), $identifier->template);
        }

        return null;
    }
}
