<?php

declare(strict_types=1);

namespace Tokei\Model\Event;

use Tempest\Database\IsDatabaseModel;
use Tempest\Database\Table;
use Tempest\Database\Virtual;
use Tempest\Validation\Rules\IsNotEmptyString;
use Tempest\Validation\Rules\MatchesRegEx;
use Tokei\Extension\Validation\Rules\IsDBSType;
use Tokei\Extension\Validation\Rules\IsValidEventState;
use Tokei\Extension\Validation\Rules\IsValidOnlineState;
use Tokei\Model\IsLocated;
use Tokei\Model\Located;

#[Table(name: 'event')]
final class Event implements Located
{
    use IsDatabaseModel;
    use IsLocated;


    #[IsDBSType]
    public string $type;

    public int $time_start;

    public int $time_end;

    #[MatchesRegEx('#^[0-9]{4}-[0-9]{2}$#u')]
    public string $time_code;

    public float $hours;

    public int $staff;

    public int $staff_external;

    public int $attendees; // "visitors" "participant"

    #[IsNotEmptyString]
    public string $title;

    public string $description = '';

    public string $audience;

    public int $is_education;

    #[IsValidOnlineState]
    public int $online;

    #[IsValidEventState]
    public int $state;

    public int $created;

    public ?int $modified = 0;


    // virtual fields
    #[Virtual]
    public float $hours_staff {
        get { return $this->staff * $this->hours; }
    }

    #[Virtual]
    public float $hours_staff_external {
        get { return $this->staff_external * $this->hours; }
    }
}
