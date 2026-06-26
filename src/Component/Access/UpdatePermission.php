<?php

declare(strict_types=1);

namespace Tokei\Component\Access;

use Tempest\DateTime\DateTime;
use Tokei\Model\Located;
use Tokei\Model\Report;
use Tokei\Model\ReportStatus;
use Tokei\Model\Timed;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class UpdatePermission implements Permission
{
    public function __construct(
        protected(set) string $name,
        protected(set) int $timeLimit = -1,
        protected(set) string $super = 'can_update_limitless',
    ) {}

    public function check(?AccessControl $accessControl, ?object $model = null): bool
    {
        if ($this->name === '' || $this->super !== '' && $accessControl->hasPermission($this->super)) {
            return true;
        }

        if ($model === null) {
            throw new \RuntimeException('Object cannot be null when checking update permission');
        }

        if ($accessControl->hasPermission($this->name)) {
            if ($model instanceof Located && $accessControl->user->seal !== 'x' && $accessControl->user->seal !== $model->seal) {
                return false;
            }

            if ($model instanceof Report && $model->report_status !== ReportStatus::OPEN->value) {
                return false;
            }

            if ($model instanceof Timed && $this->timeLimit >= 0) {
                $time = DateTime::now()->getTimestamp()->getSeconds();
                $time -= $this->timeLimit * 3600;

                return $time < $model->created;
            }

            return true;
        }

        return false;
    }
}
