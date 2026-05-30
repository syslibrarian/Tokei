<?php

declare(strict_types=1);

namespace Tokei\Extension\Twig;

enum NoteTypes: string
{
    case INFO = 'info';
    case WARNING = 'warning';
    case ERROR = 'error';
    case SUCCESS = 'success';

    public function getClass(): string
    {
        return $this->value;
    }

    public static function get(string $name): string
    {
        $name = strtolower($name);
        return match (true) {
            $name === self::WARNING->value => self::WARNING->getClass(),
            $name === self::ERROR->value => self::ERROR->getClass(),
            $name === self::SUCCESS->value => self::SUCCESS->getClass(),
            $name === self::DEV_PANDI->value => self::DEV_PANDI->getClass(),
            default => self::INFO->getClass(),
        };
    }
}
