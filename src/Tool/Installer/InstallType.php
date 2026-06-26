<?php

declare(strict_types=1);

namespace Tokei\Tool\Installer;

enum InstallType
{
    case INSTALL;
    case UPDATE;
    case BOTH;
}
