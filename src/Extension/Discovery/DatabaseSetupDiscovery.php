<?php

declare(strict_types=1);

namespace Tokei\Extension\Discovery;

use Tokei\Tool\Installer\DatabaseTable;
use Tempest\Discovery\Discovery;
use Tempest\Discovery\DiscoveryLocation;
use Tempest\Discovery\IsDiscovery;
use Tempest\Reflection\ClassReflector;

class DatabaseSetupDiscovery implements Discovery
{
    use IsDiscovery;

    public function __construct() {}

    public function discover(DiscoveryLocation $location, ClassReflector $class): void
    {
        if ($class->hasAttribute(DatabaseTable::class)) {
            $metadata = $class->getAttribute(DatabaseTable::class);
            $className = $class->getName();

            $this->discoveryItems->add($location, [$metadata, $className]);
        }
    }

    public function apply(): void
    {
        // TODO: Implement apply() method.
    }
}
