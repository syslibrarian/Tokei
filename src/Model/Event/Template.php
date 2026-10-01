<?php

declare(strict_types=1);

namespace Tokei\Model\Event;

use Tempest\Database\IsDatabaseModel;
use Tempest\Database\Table;
use Tempest\Validation\Rules\IsNotEmptyString;
use Tokei\Component\Access\CreatePermission;
use Tokei\Component\Access\DeletePermission;
use Tokei\Component\Access\UpdatePermission;
use Tokei\Extension\Validation\Rules\IsNotExistingEventTemplate;
use Tokei\Model\Routes;

#[
    Table(name: 'event_template'),
    CreatePermission('can_create_event'),
    UpdatePermission('can_update_event', 0),
    DeletePermission,
    Routes(
        '',
        '/templates',
        '/create-template',
        '/update-template/{id}',
        '/delete-template/{id}',
        'adm/events/'
    )
]
final class Template
{
    use IsDatabaseModel;

    #[IsNotEmptyString, IsNotExistingEventTemplate]
    public string $name;
    public string $description;
    public int $length;
}
