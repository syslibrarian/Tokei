<?php

declare(strict_types = 1);

namespace Tokei\Model\Klr;

use Tempest\Database\IsDatabaseModel;
use Tempest\Database\Table;
use Tokei\Model\IsLocated;
use Tokei\Model\IsReport;
use Tokei\Model\Report;
use Tokei\Model\Located;

#[Table('klr_month')]
final class KlrReport implements Report, Located
{
    use IsDatabaseModel;
    use IsReport;
    use IsLocated;

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
