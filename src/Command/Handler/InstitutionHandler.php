<?php

declare(strict_types=1);

namespace Tokei\Command\Handler;

use Tempest\CommandBus\CommandHandler;
use Tempest\DateTime\Timestamp;
use Tempest\Validation\Exceptions\ValidationFailed;
use Tokei\Command\Institution\CreateInstitution;
use Tokei\Command\Institution\DeleteInstitution;
use Tokei\Command\Institution\UpdateInstitution;
use Tokei\Command\IsHandler;
use Tokei\Model\Institution\Institution;

final class InstitutionHandler
{
    use IsHandler;

    #[CommandHandler]
    public function create(CreateInstitution $command): void
    {
        $this->transaction->begin();

        try {
            $model = Institution::create(
                name: $command->name,
                educator: $command->educator,
                seal: $command->seal,
                type: $command->type,
                created: Timestamp::now()->getSeconds()
            );

            if ($command->email !== '') {
                $model->update(email: $command->email);
            }

            if ($command->phone !== '') {
                $model->update(phone: $command->phone);
            }
        } catch (ValidationFailed $e) {
            $this->transaction->rollback();
            $this->response->set($command, $e);

            return;
        }

        $this->transaction->commit();
        $this->response->set($command, $model);
    }

    #[CommandHandler]
    public function update(UpdateInstitution $command): void
    {
        $this->transaction->begin();
        try {
            $command->model->update(
                name: $command->name,
                educator: $command->educator,
                seal: $command->seal,
                type: $command->type
            );

            if ($command->email !== '') {
                $command->model->update(
                    email: $command->email
                );
            }

            if ($command->phone !== '') {
                $command->model->update(
                    phone: $command->phone
                );
            }
        } catch (ValidationFailed $e) {
            $this->transaction->rollback();
            $this->response->set($command, $e);

            return;
        }

        $this->transaction->commit();
        $this->response->set($command, true);
    }

    #[CommandHandler]
    public function delete(DeleteInstitution $command): void
    {
        $command->model->delete();
        $this->response->set($command, true);
    }
}
