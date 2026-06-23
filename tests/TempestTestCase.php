<?php

namespace Tests;

use Tempest\Discovery\DiscoveryLocation;
use Tempest\Framework\Testing\IntegrationTest;

class TempestTestCase extends IntegrationTest
{
    public function discoverTestLocations(): array
    {
        return [new DiscoveryLocation('Tests', $this->root . '/tests/config/')];
    }
}
