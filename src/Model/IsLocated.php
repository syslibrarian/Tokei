<?php

declare(strict_types=1);

namespace Tokei\Model;

use Tempest\Validation\Rules\MatchesRegEx;
use Tokei\Extension\Validation\Rules\IsExistingSeal;

trait IsLocated
{
    #[MatchesRegEx('/^[0-9]{3}[a-z]?$/u'), IsExistingSeal]
    public string $seal;
}
