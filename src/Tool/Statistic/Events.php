<?php

declare(strict_types=1);

namespace Tokei\Tool\Statistic;

use Tokei\Model\Event\DBSSection;
use Tokei\Model\Event\Event;

use Tokei\Model\TimeCode;
use function Tempest\Support\Json\decode;
use function Tempest\Support\Json\encode;

final class Events
{
    /** @var array<string, DBSContainer> */
    protected(set) array $containers = [];

    /** @var Event[] */
    protected(set) array $events = [];
    protected(set) TimeCode $timeCode;
    protected(set) int $untracked = 0;

    public int $totalAttendees {
        get {
            $total = 0;

            foreach ($this->containers as $container) {
                $total += $container->attendees;
            }

            return $total;
        }
    }

    public function __construct(
        public string $seal,
        public string|int $year,
        public string|int $month,
        bool $fromString = false,
    ) {
        $this->buildTimeCode();
        if ($fromString === false) {
            $this->checkArguments();
            $this->loadEvents();
        }
    }

    private function buildTimeCode(): void
    {
        $this->timeCode = TimeCode::fromParts($this->year, $this->month);
    }

    private function checkArguments(): void
    {
        if (!preg_match("/^\d{3}[a-z]?$/", $this->seal)) {
            throw new \InvalidArgumentException('Seal must be in format XXX(a)');
        }
    }

    private function loadEvents(): void
    {
        $this->events = Event::select()
            ->where('time_code = ? AND seal = ?', $this->timeCode, $this->seal)
            ->all();

        if (!empty($this->events)) {
            $this->buildStatistic();
        }
    }

    private function buildStatistic(): void
    {
        // create sections for calculation
        foreach (DBSSection::SECTIONS as $sections) {
            foreach ($sections as $number => $name) {
                $this->containers[$number] = new DBSContainer(
                    $number,
                    $name,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0
                );
            }
        }

        // loop all events of month
        foreach ($this->events as $event) {
            if (isset($this->containers[$event->type])) {
                if ($this->containers[$event->type]->addEvent($event) === false) {
                    $this->untracked++;
                    continue;
                }
                continue;
            }

            $this->untracked++;
        }
    }

    public function exportJson(): string
    {
        $containers = [];
        foreach ($this->containers as $container) {
            $containers[] = $container->exportJson();
        }

        return encode([
            'seal' => $this->seal,
            'timeCode' => $this->timeCode->timeCode,
            'containers' => $containers
        ]);
    }

    public static function fromJsonString(string $json): self
    {
        $tmp = decode($json);

        list($year, $month) = explode('-', $tmp['timeCode'] ?? '0000-00');
        $events = new Events($tmp['seal'] ?? '000', $year, $month, true);

        $containers = $tmp['containers'] ?? [];
        foreach ($containers as $container) {
            $containerObj = (is_string($container)) ? DBSContainer::fromJsonString($container) : DBSContainer::fromArray($container);

            // only formal check for empty string
            if ($containerObj->number !== '') {
                $events->containers[$containerObj->number] = $containerObj;
            }
        }

        return $events;
    }
}
