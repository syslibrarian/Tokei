<?php

declare(strict_types=1);

namespace Tokei\Command\Handler;

use Tempest\CommandBus\CommandHandler;
use Tempest\Database\Exceptions\DatabaseException;
use Tempest\DateTime\Timestamp;
use Tokei\Command\IsHandler;
use Tokei\Command\Klr\BuildFromReports;
use Tokei\Command\Klr\CreateMonths;
use Tokei\Model\Klr\KlrHelper;
use Tokei\Model\Klr\KlrReport;
use Tokei\Model\Location\Location;
use Tokei\Model\Location\MonthlyReport;
use Tokei\Model\ReportStatus;
use Tokei\Model\TimeCode;

use function Tempest\Database\query;

final class KlrHandler
{
    use IsHandler;

    #[CommandHandler]
    public function createMonths(CreateMonths $command): void
    {
        $this->transaction->begin();

        try {
            $locations = Location::select()->all();
            $existingMonths = KlrHelper::getAllMonthsForCommand($command->year);

            $rawQuery = query(KlrReport::class);

            $month = 1;
            while ($month <= 12) {
                foreach ($locations as $location) {
                    if (isset($existingMonths[$month][$location->seal])) {
                        continue;
                    }

                    $rawQuery
                        ->insert(
                            report_status: ReportStatus::OPEN->value,
                            seal: $location->seal,
                            month: $month,
                            year: $command->year,
                            time_code: TimeCode::fromParts($command->year, $month),
                            created: Timestamp::now()->getSeconds(),
                        )
                        ->execute();
                }
                $month++;
            }
        } catch (DatabaseException $e) {
            $this->transaction->rollback();
            $this->response->set($command, $e);
            return;
        }

        $this->transaction->commit();
        $this->response->set($command, true);
    }

    #[CommandHandler]
    public function buildFromReports(BuildFromReports $command): void
    {
        $this->transaction->begin();
        try {
            $timeCode = TimeCode::fromParts($command->year, $command->month);
            $reports = MonthlyReport::select()->where('time_code', $timeCode)->all();
            $months = KlrHelper::getSortedMonths((string) $timeCode);

            foreach ($reports as $report) {
                if (! isset($months[$report->seal])) {
                    KlrReport::create(
                        report_status: ReportStatus::isUpdated($report->report_status) ? ReportStatus::UPDATED->value : ReportStatus::CLOSE->value,
                        seal: $report->seal,
                        year: $report->year,
                        month: $report->month,
                        time_code: $timeCode,
                        circulations: $report->circulations,
                        visits: $report->visits_total,
                        attendees: $report->events->totalAttendees,
                        created: Timestamp::now()->getSeconds(),
                        reported: Timestamp::now()->getSeconds(),
                    );
                } else {
                    $months[$report->seal]->update(
                        report_status: ReportStatus::isUpdated($report->report_status) ? ReportStatus::UPDATED->value : ReportStatus::CLOSE->value,
                        circulations: $report->circulations,
                        visits: $report->visits_total,
                        attendees: $report->events->totalAttendees,
                        reported: Timestamp::now()->getSeconds(),
                    );
                }

                $report->update(
                    report_status: ReportStatus::CLOSE->value,
                    events_raw: $report->events->exportJson(),
                    modified: Timestamp::now()->getSeconds(),
                );
            }
        } catch (DatabaseException $e) {
            $this->transaction->rollback();
            $this->response->set($command, $e);
            return;
        }

        $this->transaction->commit();
        $this->response->set($command, true);
    }
}
