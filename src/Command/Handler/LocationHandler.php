<?php

declare(strict_types=1);

namespace Tokei\Command\Handler;

use Tempest\CommandBus\CommandHandler;
use Tempest\Validation\Exceptions\ValidationFailed;
use Tokei\Command\IsHandler;
use Tokei\Command\Location\CreateLocation;
use Tokei\Command\Location\DeleteLocation;
use Tokei\Command\Location\UpdateLocation;
use Tokei\Model\Location\Location;

class LocationHandler
{
    use IsHandler;

    #[CommandHandler]
    public function create(CreateLocation $location): void
    {
        $this->transaction->begin();
        try {
            $entry = Location::create(
                name: $location->name,
                seal: $location->seal,
                street: $location->street,
                city: $location->city,
                zip_code: $location->zip_code,
                fte: $location->fte,
                fte_consumed: $location->fte_consumed,
                area: $location->area,
            );
        } catch (ValidationFailed $e) {
            $this->transaction->rollback();
            $this->response->set($location, $e);

            return;
        }

        $this->transaction->commit();
        $this->response->set($location, $entry);
    }

    #[CommandHandler]
    public function update(UpdateLocation $location): void
    {
        $this->transaction->begin();
        try {
            $location->model->update(
                name: $location->name,
                street: $location->street,
                city: $location->city,
                zip_code: $location->zip_code,
                fte: $location->fte,
                fte_consumed: $location->fte_consumed,
                area: $location->area
            );

            if ($location->seal !== $location->model->seal) {
                $location->model->update(
                    seal: $location->seal,
                );
            }
        } catch (ValidationFailed $e) {
            $this->transaction->rollback();
            $this->response->set($location, $e);

            return;
        }

        $this->transaction->commit();
        $this->response->set($location, true);
    }

    public function delete(DeleteLocation $location): void
    {
        // TODO: implement when events are ready.
    }
}
