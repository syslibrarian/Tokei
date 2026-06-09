<?php

declare(strict_types=1);

namespace Tokei\Model\Location;

use Tempest\Database\IsDatabaseModel;
use Tempest\Database\Table;
use Tempest\Validation\Rules\IsNotEmptyString;
use Tempest\Validation\Rules\MatchesRegEx;
use Tokei\Extension\Validation\Rules\IsNotExistingSeal;

#[Table(name: 'location')]
final class Location
{
    use IsDatabaseModel;

    #[IsNotEmptyString]
    public string $name;

    #[MatchesRegEx('/^[0-9]{3}[a-z]?$/u'), IsNotExistingSeal()]
    public string $seal;

    #[IsNotEmptyString]
    public string $street;

    #[IsNotEmptyString]
    public string $city;

    #[MatchesRegEx('/^[0-9]{5}$/u')] // current german postal code
    public string $postal_code;

    public float $fte;

    public float $fte_consumed;

    public float $area;

    public int $created;

    public ?int $modified;

    public string $klr_code;
}