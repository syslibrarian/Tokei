<?php

declare(strict_types=1);

namespace Tokei\Component\Revision;

use Tokei\Model\Revision\Log;
use Tokei\Model\User\User;

interface RevisionManager
{
    public static function forModel(object|string $model): static;

    public function logModel(?User $user, object $model): Log;

    public function getLastLog(): Log;

    public function getLogs(int $limit, int $offset = 0): array;

    public function parseHistory(?array $logs = null): string;

    public function parseLog(?Log $log = null): string;
}
