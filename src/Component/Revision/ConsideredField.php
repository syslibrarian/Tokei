<?php

declare(strict_types=1);

namespace Tokei\Component\Revision;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class ConsideredField
{
    public function __construct() {}
}
