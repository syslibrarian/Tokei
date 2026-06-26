<?php

declare(strict_types=1);

namespace Tokei\Tool\Installer;

interface SetupStep
{
    public function getInformation();

    public function execute();
}
