<?php

declare(strict_types=1);

namespace Tokei\Component\Access;

use Tokei\Model\User\User;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class DeletePermission implements Permission
{
    protected(set) int $timeLimit = -1;

    public function __construct(
        protected(set) string $name = '',
        protected(set) string $super = '',

    ) {}

    public function check(?AccessControl $accessControl, ?object $model = null): bool
    {
        if ($this->name === '') {
            throw new \RuntimeException('Delete permission should not be empty');
        }

        if (($model instanceof User) && $model->id === $accessControl->user->id) {
            return false;
        }

        return $accessControl->canDelete($this->name);
    }
}
