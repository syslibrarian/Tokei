<?php

declare(strict_types = 1);

namespace Tokei\Model\Klr;

use Tempest\Database\IsDatabaseModel;
use Tempest\Database\Table;

#[Table('klr_month')]
final class Month
{
    use IsDatabaseModel;

    public int $status;
    public string $seal;
    public int $year;
    public int $month;
    public string $time_code;
    public int $circulations; // product code:
    public int $visits; // product code:
    public int $attendees; // product code:
    public int $created;
    public ?int $reported;
    public ?int $modified;
}
