<?php

declare(strict_types=1);

use Tempest\View\Renderers\TwigConfig;

return new TwigConfig(
    viewPaths: [
        dirname(__DIR__) . '/views/page',
    ],
    debug: true,
    autoReload: true,
);
