<?php

declare(strict_types=1);

namespace Tokei\Tool\Statistic;

use Tokei\Model\Event\Event;

use function Tempest\Support\Json\decode;
use function Tempest\Support\Json\encode;

final class DBSContainer
{
    public float $workHoursTotal {
        get { return $this->workHours + $this->workHoursExternal; }
    }

    public function __construct(
        protected(set) string $number,
        protected(set) string $name,
        protected(set) int $amount,
        protected(set) int $attendees,
        protected(set) float $hours,
        protected(set) int $staff,
        protected(set) int $staffExternal,
        protected(set) float $workHours,
        protected(set) float $workHoursExternal
    ) {}

    public function addEvent(Event $event): bool
    {
        if ($event->type !== $this->number) {
            return false;
        }

        $this->amount++;
        $this->attendees += $event->attendees;
        $this->hours += $event->hours;
        $this->workHours += $event->hours_staff;
        $this->workHoursExternal += $event->hours_staff_external;
        $this->staff += $event->staff;
        $this->staffExternal += $event->staff_external;

        return true;
    }

    public function exportJson(): string
    {
        return encode([
            'number' => $this->number,
            'name' => $this->name,
            'amount' => $this->amount,
            'attendees' => $this->attendees,
            'staff' => $this->staff,
            'staffExternal' => $this->staffExternal,
            'hours' => $this->hours,
            'workHours' => $this->workHours,
            'workHoursExternal' => $this->workHoursExternal,
        ]);
    }

    public static function fromJsonString(string $json): self
    {
        $data = decode($json);

        return self::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['number'] ?? '',
            $data['name'] ?? '',
            $data['amount'] ?? 0,
            $data['attendees'] ?? 0,
            $data['hours'] ?? 0,
            $data['staff'] ?? 0,
            $data['staffExternal'] ?? 0,
            $data['workHours'] ?? 0,
            $data['workHoursExternal'] ?? 0,
        );
    }
}
