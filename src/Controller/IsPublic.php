<?php

declare(strict_types=1);

namespace Tokei\Controller;

trait IsPublic
{
    protected function beforeInit(): void
    {
        $this->registerNavigation('header');
        $this->registerNavigation('footer');
    }

    protected function afterInit(): void
    {}

    abstract protected function registerNavigation(string $name): void;

    abstract protected function registerViewPath(string $namespace, string $path): void;
}
