<?php

declare(strict_types=1);

namespace Tokei\Command\Handler;

use Tempest\CommandBus\CommandHandler;
use Tempest\Database\Exceptions\DatabaseException;
use Tempest\DateTime\Timestamp;
use Tokei\Command\IsHandler;
use Tokei\Command\Klr\BuildFromReports;
use Tokei\Command\Klr\CreateMonths;
use Tokei\Command\Klr\UpdateFromReports;
use Tokei\Model\Klr\KlrHelper;
use Tokei\Model\Klr\Month;
use Tokei\Model\Location\Location;
use Tokei\Model\Location\Report;
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

            $rawQuery = query(Month::class);

            $month = 1;
            while ($month <= 12) {
                foreach ($locations as $location) {
                    if (isset($existingMonths[$month][$location->seal])) {
                        continue;
                    }

                    $rawQuery->insert(
                        status: ReportStatus::OPEN->value,
                        seal: $location->seal,
                        month: $month,
                        year: $command->year,
                        time_code: TimeCode::fromParts($command->year, $month),
                        created: Timestamp::now()->getSeconds()
                    )->execute();
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
            $reports = Report::select()->where('timeCode', $timeCode)->all();
            $months = KlrHelper::getSortedMonths((string) $timeCode);

            foreach ($reports as $report) {
                if (!isset($months[$report->seal])) {
                    Month::create(
                        status: ReportStatus::CLOSE->value,
                        seal: $report->seal,
                        year: $report->year,
                        month: $report->month,
                        time_code: $timeCode,
                        circulations: $report->circulations,
                        visits: $report->visits_total,
                        attendees: $report->events->totalAttendees,
                        created: Timestamp::now()->getSeconds(),
                        reported: Timestamp::now()->getSeconds()
                    );
                } else {
                    $months[$report->seal]->update(
                        status: ReportStatus::CLOSE->value,
                        circulations: $report->circulations,
                        visits: $report->visits_total,
                        attendees: $report->events->totalAttendees,
                        reported: Timestamp::now()->getSeconds()
                    );
                }

                $report->update(
                    status: ReportStatus::CLOSE->value,
                    events_raw: $report->events->exportJson(),
                    modified: Timestamp::now()->getSeconds()
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

    #[CommandHandler]
    public function updateFromReports(UpdateFromReports $command): void
    {
        $this->transaction->begin();
        try {
            $timeCode = TimeCode::fromParts($command->year, $command->month);
            $reports = Report::select()->where('timeCode', $timeCode)->all();
            $months = KlrHelper::getSortedMonths((string) $timeCode);

            foreach ($reports as $report) {
               if ($report->status === ReportStatus::CLOSE) {
                   continue;
               }

               $months[$report->seal]->update(
                   status: ReportStatus::UPDATED->value,
                   circulations: $report->circulations,
                   visits: $report->visits_total,
                   attendees: $report->events->totalAttendees,
                   reported: Timestamp::now()->getSeconds()
               );

                $report->update(
                    status: ReportStatus::CLOSE->value,
                    events_raw: $report->events->exportJson(),
                    modified: Timestamp::now()->getSeconds()
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
