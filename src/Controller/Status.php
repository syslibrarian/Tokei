<?php

declare(strict_types=1);

namespace Tokei\Controller;

enum Status: string
{
    case ERROR = 'error';
    case SUCCESS = 'success';
    case NORMAL = 'normal';
}
