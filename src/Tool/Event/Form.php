<?php

declare(strict_types=1);

namespace Tokei\Tool\Event;

use Tokei\Model\Event\DBSSection;

final class Form
{
    protected function __construct(protected(set) FormType $form)
    {}

    public function isBase(): bool
    {
        return ($this->form === FormType::SYSTEM);
    }

    public function getDefaults(): array
    {
        return [];
    }

    public function getDBSForForm(): \Generator
    {
        return DBSSection::getForForm();
    }

    public function asHiddenField(): array
    {
        return [];
    }

    public function getSeal(): string
    {
        return ''; // select seal from user
    }
}
