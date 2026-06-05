<?php

declare(strict_types=1);

namespace Tokei\Tool\User;

use Tokei\Event\User\PermissionsPrepare;
use Tokei\Model\User\Role;
use Tempest\Container\Singleton;
use Tempest\EventBus\EventBus;
use Tempest\Http\Request;

#[Singleton]
final class Permissions
{
    private(set) array $permissions;
    private(set) array $permissionValues;

    public function __construct(
        protected EventBus $eventBus,
    ) {
        $this->setBasePermissions();
    }

    public function addPermission(string $groupName, string $name): self
    {
        $this->addPermissions($groupName, [$name]);

        return $this;
    }

    public function addPermissions(string $groupName, array $permissions): self
    {
        if (! array_key_exists($groupName, $this->permissions)) {
            $this->permissions[$groupName] = [];
        }

        foreach ($permissions as $permission) {
            $this->permissions[$groupName][] = $permission;
        }

        return $this;
    }

    public function buildForCommand(Request $request): array
    {
        $permissions = [];

        foreach ($this->permissions as $group) {
            foreach ($group as $permission) {
                $permissions[$permission] = (int) $request->get($permission, 0);
            }
        }

        return $permissions;
    }

    public function buildForForm(?Role $role = null): \Generator
    {
        $permissionFunc = static function (array $permissions, ?Role $role = null): \Generator {
            foreach ($permissions as $permission) {
                yield ['name' => $permission, 'value' => $role !== null ? $role->hasPermission($permission) : 0];
            }
        };

        foreach ($this->permissions as $groupName => $permissions) {
            yield ['name' => $groupName, 'permissions' => $permissionFunc($permissions, $role)];
        }
    }

    private function setBasePermissions(): void
    {
        $this->permissions = [
            'general' => [
                'can_create_location',
                'can_update_location',
            ],
            'event' => [
                'can_create_event',
                'can_update_event',
                'can_create_institutions',
                'can_update_institutions',
            ],
            'location' => [
                'can_create_report',
                'can_update_report',
                'can_close_report',
            ],
            'klr' => [
                'can_create_klr',
                'can_update_klr',
                'can_close_klr'
            ],
            'super' => [
                'can_update_limitless',
                'can_create_roles',
                'can_update_roles',
                'can_reopen_report',
                'can_reopen_klr',
                'can_delete'
            ]
        ];

        $this->eventBus->dispatch(new PermissionsPrepare($this));
    }
}
