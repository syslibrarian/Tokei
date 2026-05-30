<?php

declare(strict_types=1);

namespace Tokei\Model\Revision;

enum Type: int
{
    case INITIAL = 1;
    case UPDATE = 2;
}
