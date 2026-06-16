<?php

declare(strict_types=1);

namespace Tokei\Model\Location;

use Tempest\Database\IsDatabaseModel;
use Tempest\Database\Table;
use Tempest\Database\Virtual;
use Tokei\Model\IsLocated;
use Tokei\Model\IsReport;
use Tokei\Model\Report;
use Tokei\Model\ReportStatus;
use Tokei\Tool\Statistic\Events;
use Tokei\Model\Located;

#[Table('location_report')]
final class MonthlyReport implements Report, Located
{
    use IsDatabaseModel;
    use IsReport;
    use IsLocated;

    public string $time_code;
    public int $year;
    public int $month;
    public int $circulations;
    public int $visits;
    public int $visits_manual;
    public int $open_hours;
    public int $open_library_hours;
    public int $media_packages;
    public int $shifts; // in hours
    public int $covers_received;
    public int $covers_given;
    public string $events_raw; // json object - calculatet every time until close;
    public int $created;
    public ?int $modified;
    public ?int $updated; // for closed reports after new caluclation.

    // virtual fields
    #[Virtual]
    public int $visits_total {
        get { return $this->visits + $this->visits_manual; }
    }

    #[Virtual]
    public Events $events {
        get {
            if (ReportStatus::isOpen($this->report_status) || ReportStatus::isUpdated($this->report_status)) {
                if ($this->tmp === null) {
                    $this->tmp = new Events($this->seal, $this->year, $this->month);
                }

                return $this->tmp;
            }

            return Events::fromJsonString($this->events_raw);
        }
    }

    #[Virtual]
    protected ?Events $tmp = null;
}
