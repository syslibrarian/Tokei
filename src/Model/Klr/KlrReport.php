<?php

declare(strict_types=1);

namespace Tokei\Model\Klr;

use Tempest\Database\IsDatabaseModel;
use Tempest\Database\Table;
use Tokei\Component\Access\CreatePermission;
use Tokei\Component\Access\DeletePermission;
use Tokei\Component\Access\UpdatePermission;
use Tokei\Model\IsLocated;
use Tokei\Model\IsReport;
use Tokei\Model\Located;
use Tokei\Model\Report;

#[
    Table('klr_month'),
    CreatePermission('can_create_reports'),
    UpdatePermission('can_close_report'),
    DeletePermission,
]
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
