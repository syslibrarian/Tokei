<?php

declare(strict_types=1);

namespace Tokei\Tool\Event;

use Tempest\Intl\Translator;
use Tokei\Model\Institution\Institution;
use Tokei\Model\Location\Location;
use function Tempest\Container\get;

final class Form
{
    public const array TIME_FACTORS = [
        ['value' => '+45', 'name' => 'tokei.adm.events.time_factor_short'],
        ['value' => '+60', 'name' => 'tokei.adm.events.time_factor_normal'],
        ['value' => '+90', 'name' => 'tokei.adm.events.time_factor_long'],
    ];

    /** @var Institution[] */
    private array $institutions;

    private function __construct(
        protected(set) FormType $type,
        protected(set) ?Location $location,
    ) {
        $this->getInstitutions();
    }

    public function isBase(): bool
    {
        return $this->type === FormType::SYSTEM || $this->type === FormType::EVENT;
    }

    public function getTypes(): \Generator
    {
        return DBSSection::getForForm($this->type);
    }

    public function getTimeFactors(): \Generator
    {
        foreach (self::TIME_FACTORS as $timeFactor) {
            yield [
                'name' => get(Translator::class)->translate($timeFactor['name']),
                'value' => $timeFactor['value'],
            ];
        }
    }

    public function getHiddenFields(): array
    {
        // select seal from user here
        return match ($this->type) {
            FormType::PRE_SCHOOL, FormType::SCHOOL => ['seal' => $this->location->seal, 'online' => 1, 'audience' => 'young'],
            default => [],
        };
    }

    public function getDatalist(): \Generator
    {
        foreach ($this->institutions as $institution) {
            yield ['value' => $institution->name . ' | ' . $institution->educator . ' (ID: ' . $institution->id . ')'];
        }
    }

    public static function getFor(string $type, ?Location $location = null): self
    {
        return match (true) {
            FormType::PRE_SCHOOL->value === $type => new self(FormType::PRE_SCHOOL, $location),
            FormType::SCHOOL->value === $type => new self(FormType::SCHOOL, $location),
            default => new self(FormType::EVENT, $location),
        };
    }

    private function getInstitutions(): void
    {
        $this->institutions = Institution::select()->where('seal = ? AND type = ?', $this->location->seal, $this->type->value)->all();
    }
}
