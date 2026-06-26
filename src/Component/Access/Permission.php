<?php

declare(strict_types=1);

namespace Tokei\Component\Access;

interface Permission
{
    public string $name { get; }
    public string $super { get; }
    public int $timeLimit { get; }

    public function check(?AccessControl $accessControl, ?object $model = null): bool;
}
