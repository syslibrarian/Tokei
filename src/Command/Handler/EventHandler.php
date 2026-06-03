<?php

declare(strict_types=1);

namespace Tokei\Command\Handler;

use Tempest\CommandBus\CommandHandler;
use Tempest\DateTime\Timestamp;
use Tempest\Validation\Exceptions\ValidationFailed;
use Tempest\Validation\Rules\IsNotEmptyString;
use Tempest\Validation\Validator;
use Tokei\Command\Event\CreateEvent;
use Tokei\Command\Event\UpdateEvent;
use Tokei\Command\IsHandler;
use Tokei\Model\Event\DBSSection;
use Tokei\Model\Event\Event;
use Tokei\Model\Event\EventHelper;
use function Tempest\Container\get;

class EventHandler
{
    use IsHandler;

    #[CommandHandler]
    public function create(CreateEvent $command): void
    {
        $this->transaction->begin();
        try {
            $this->checkTimeStrings($command->startDateTime, $command->endTime);

            $startTime = EventHelper::convertToDateTime($command->startDateTime);
            $endTime = EventHelper::calculateEnd($startTime, $command->endTime);
            $this->timeFlip($startTime, $endTime);

            $command->audience = DBSSection::getAudience($command->audience, $command->type);

            $event = Event::create(
                seal: $command->seal,
                type: $command->type,
                time_start: $startTime,
                time_end: $endTime,
                time_code: EventHelper::buildTimeCode($startTime),
                hours: EventHelper::calculateHours($startTime, $endTime),
                staff: $command->staff,
                staff_external: $command->staff_external,
                attendees: $command->attendees,
                title: $command->title,
                description: $command->description,
                online: $command->online,
                state: $command->state,
                created: Timestamp::now()->getSeconds()
            );
        } catch (ValidationFailed $e) {
            $this->transaction->rollback();
            $this->response->set($command, $e);
            return;
        }

        $this->transaction->commit();
        $this->response->set($command, $event);
    }

    #[CommandHandler]
    public function update(UpdateEvent $command): void
    {
        $this->transaction->begin();
        try {
            $this->checkTimeStrings($command->startDateTime, $command->endTime);
            $startTime = EventHelper::convertToDateTime($command->startDateTime);
            $endTime = EventHelper::calculateEnd($startTime, $command->endTime);
            $this->timeFlip($startTime, $endTime);

            $command->audience = DBSSection::getAudience($command->audience, $command->type);

            $command->model->update(
                seal: $command->seal,
                type: $command->type,
                time_start: $startTime,
                time_end: $endTime,
                time_code: EventHelper::buildTimeCode($startTime),
                hours: EventHelper::calculateHours($startTime, $endTime),
                staff: $command->staff,
                staff_external: $command->staff_external,
                attendees: $command->attendees,
                title: $command->title,
                description: $command->description,
                online: $command->online,
                state: $command->state,
                modified: Timestamp::now()->getSeconds()
            );
        } catch (ValidationFailed $e) {
            $this->transaction->rollback();
            $this->response->set($command, $e);
            return;
        }

        $this->transaction->commit();
        $this->response->set($command, $command);
    }

    protected function timeFlip(int &$startTime, int &$endTime): void
    {
        if ($startTime > $endTime) {
            $tmp = $startTime;
            $startTime = $endTime;
            $endTime = $tmp;
        }
    }

    protected function checkTimeStrings(string $startTime, string $endTime): void
    {
        $failingRules['startDateTime'] = get(Validator::class)->validateValue($startTime, [new IsNotEmptyString()]);
        $failingRules['endTime'] = get(Validator::class)->validateValue($endTime, [new IsNotEmptyString()]);

        if (!empty($failingRules['startDateTime']) || !empty($failingRules['endTime'])) {
            throw new ValidationFailed($failingRules);
        }
    }
}
