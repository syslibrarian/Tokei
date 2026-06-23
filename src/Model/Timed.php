<?php

declare(strict_types=1);

namespace Tokei\Model;

interface Timed
{
    public int $created_time { get; set; }
    public int $modified_time { get; set; }
}
