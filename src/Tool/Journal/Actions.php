<?php

declare(strict_types=1);

namespace Tokei\Tool\Journal;

enum Actions: string
{
    case SYS_CREATE = 'system.create';
    case SYS_UPDATE = 'system.update';
    case SYS_DELETE = 'system.delete';
    case CREATE = 'create';
    case UPDATE = 'update';
    case DELETE = 'delete';
}
