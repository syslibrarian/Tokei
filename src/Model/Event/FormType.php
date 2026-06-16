<?php

declare(strict_types=1);

namespace Tokei\Model\Event;

enum FormType: string
{
    case PRE_SCHOOL = 'pre-school';
    case SCHOOL = 'school';
    case EVENT = 'event';
    case SYSTEM = 'system';
}
