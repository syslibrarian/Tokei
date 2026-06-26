<?php

declare(strict_types=1);

namespace Tokei\Controller;

trait IsPublic
{
    protected function extend(): void
    {
        $this->registerNavigation('header');
        $this->registerNavigation('footer');
        parent::extend();
    }

    abstract protected function registerNavigation(string $name): void;

    abstract protected function registerViewPath(string $namespace, string $path): void;
}
