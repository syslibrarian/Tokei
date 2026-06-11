<?php

declare(strict_types=1);

namespace Tokei\Tool\Event;

enum FormType: string
{
    case PRE_SCHOOL = 'pre-school';
    case SCHOOL = 'school';
    case EVENT = 'event';
    case SYSTEM = 'system';
}
