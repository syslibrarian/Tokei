<?php

declare(strict_types=1);

namespace Tokei\Model;

interface Timed
{
    public int $created { get; }
    public int $modified { get; }
}
