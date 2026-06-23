<?php

declare(strict_types=1);

namespace Tokei\Command\Handler;

use Tempest\CommandBus\CommandHandler;
use Tempest\Database\Exceptions\DatabaseException;
use Tempest\DateTime\Timestamp;
use Tempest\Validation\Exceptions\ValidationFailed;
use Tokei\Command\IsHandler;
use Tokei\Command\Location\CreateLocation;
use Tokei\Command\Location\CreateReports;
use Tokei\Command\Location\DeleteLocation;
use Tokei\Command\Location\UpdateLocation;
use Tokei\Command\Location\UpdateReport;
use Tokei\Model\Location\Location;
use Tokei\Model\Location\LocationHelper;
use Tokei\Model\Location\MonthlyReport;
use Tokei\Model\ReportStatus;
use Tokei\Model\TimeCode;

use function Tempest\Database\query;

final class LocationHandler
{
    use IsHandler;

    #[CommandHandler]
    public function create(CreateLocation $command): void
    {
        $this->transaction->begin();
        try {
            $entry = Location::create(
                name: $command->name,
                seal: $command->seal,
                street: $command->street,
                city: $command->city,
                postal_code: $command->postal_code,
                fte: $command->fte,
                fte_consumed: $command->fte_consumed,
                area: $command->area,
                klr_code: $command->klrCode,
                created: Timestamp::now()->getSeconds(),
            );
        } catch (ValidationFailed $e) {
            $this->transaction->rollback();
            $this->response->set($command, $e);

            return;
        }

        $this->transaction->commit();
        $this->response->set($command, $entry);
    }

    #[CommandHandler]
    public function update(UpdateLocation $command): void
    {
        $this->transaction->begin();
        try {
            $command->model->update(
                name: $command->name,
                street: $command->street,
                city: $command->city,
                postal_code: $command->postal_code,
                fte: $command->fte,
                fte_consumed: $command->fte_consumed,
                area: $command->area,
                klr_code: $command->klrCode,
                modified: Timestamp::now()->getSeconds(),
            );

            /*if ($command->seal !== $command->model->seal) {
             * $command->model->update(
             * seal: $command->seal,
             * );
             * }*/
            // seals should not be updated afer creation.
        } catch (ValidationFailed $e) {
            $this->transaction->rollback();
            $this->response->set($command, $e);

            return;
        }

        $this->transaction->commit();
        $this->response->set($command, true);
    }

    #[CommandHandler]
    public function delete(DeleteLocation $command): void
    {
        // TODO: implement when events are ready.
    }

    #[CommandHandler]
    public function createReports(CreateReports $command): void
    {
        $this->transaction->begin();

        try {
            $locations = Location::select()->all();
            $reports = LocationHelper::getAllReportsForCommand($command->year);

            $rawQuery = query(MonthlyReport::class);

            $month = 1;
            while ($month <= 12) {
                foreach ($locations as $location) {
                    if (isset($reports[$month][$location->seal])) {
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
    public function updateReport(UpdateReport $command): void
    {
        $this->transaction->begin();

        try {
            $command->model->update(
                report_status: ReportStatus::isClose($command->model->report_status) ? ReportStatus::UPDATED->value : $command->model->report_status,
                modified: Timestamp::now()->getSeconds(),
                circulations: $command->circulations,
                visits: $command->visits,
                visits_manual: $command->visitsManual,
                open_hours: $command->openHours,
                open_library_hours: $command->openLibraryHours,
                media_packages: $command->mediaPackages,
                shifts: $command->shifts,
                covers_received: $command->coversReceived,
                covers_given: $command->coversGiven,
                staff_external: $command->staffExternal,
                staff_external_hours: $command->staffExternalHours,
                staff_grant: $command->staffGrant,
                staff_grant_hours: $command->staffGrantHours,
                staff_volunteer: $command->staffVolunteer,
                staff_volunteer_hours: $command->staffVolunteerHours,
            );
        } catch (ValidationFailed $e) {
            $this->transaction->rollback();
            $this->response->set($command, $e);
            return;
        }

        $this->transaction->commit();
        $this->response->set($command, true);
    }
}
