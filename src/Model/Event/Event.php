<?php

declare(strict_types=1);

namespace Tokei\Model\Event;

use Tempest\Database\IsDatabaseModel;
use Tempest\Database\Virtual;
use Tempest\Validation\Rules\IsNotEmptyString;
use Tempest\Validation\Rules\MatchesRegEx;
use Tokei\Extension\Validation\Rules\IsDBSType;
use Tokei\Extension\Validation\Rules\IsValidOnlineState;

final class Event
{
    use IsDatabaseModel;

    #[MatchesRegEx('#^[0-9]{3}[a-z]?$#u')]
    public string $seal;

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

    #[IsValidOnlineState]
    public int $online;

    #[IsValidEventState]
    public int $state;

    public int $created;

    public ?int $modified = 0;

    public string $audience;

    // virtual fields
    #[Virtual]
    public float $staffHours {
        get { return $this->staff * $this->hours; }
    }

    public float $externalStaffHours {
        get { return $this->staff_external * $this->hours; }
    }
}
