<?php

declare(strict_types=1);

namespace Tokei\Console;

use Tempest\CommandBus\CommandBus;
use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;
use Tempest\Validation\Rules\IsEmail;
use Tempest\Validation\Rules\IsNotEmptyString;
use Tempest\Validation\Rules\IsPassword;
use Tokei\Command\User\CreateRole;
use Tokei\Command\User\CreateUser;
use Tokei\Model\Navigation\Item;
use Tokei\Model\Navigation\Navigation;
use Tokei\Tool\Installer\Database\EventCreateTable;
use Tokei\Tool\Installer\Database\InstitutionCreateTable;
use Tokei\Tool\Installer\Database\KlrMonthCreateTable;
use Tokei\Tool\Installer\Database\LocationCreateTable;
use Tokei\Tool\Installer\Database\NavigationCreateTable;
use Tokei\Tool\Installer\Database\NavigationItemCreateTable;
use Tokei\Tool\Installer\Database\ReportCreateTable;
use Tokei\Tool\Installer\Database\UserCreateTable;
use Tokei\Tool\Installer\Database\UserPermissionCreateTable;
use Tokei\Tool\Installer\Database\UserRoleCreateTable;
use Tokei\Tool\Role\Permissions;

final class Install
{
    public function __construct(
        protected(set) Console $console,
        protected(set) CommandBus $commandBus,
    ) {}

    #[ConsoleCommand(name: 'tokei:install')]
    public function installDatabase(): void
    {
        $this->console->info('Start installing Tokei database');
        new NavigationCreateTable()->execute();
        new NavigationItemCreateTable()->execute();
        new UserRoleCreateTable()->execute();
        new UserPermissionCreateTable()->execute();
        new UserCreateTable()->execute();
        new LocationCreateTable()->execute();
        new InstitutionCreateTable()->execute();
        new EventCreateTable()->execute();
        new ReportCreateTable()->execute();
        new KlrMonthCreateTable()->execute();

        $this->console->info('Finished creating database tables, installing base information.');
        Navigation::create(name: 'header', is_system: true, view_name: '_navigation.tpl');
        Navigation::create(name: 'footer', is_system: true, view_name: '_navigation.tpl');
        Navigation::create(name: 'adm_header', is_system: true, is_admin: true, view_name: '_navigation.tpl');
        Navigation::create(name: 'adm_footer', is_system: true, is_admin: true, view_name: '_navigation.tpl');
        Navigation::create(name: 'adm_general', is_system: true, is_admin: true, view_name: '_navigation.tpl');
        Navigation::create(name: 'adm_events', is_system: true, is_admin: true, view_name: '_navigation.tpl');
        Navigation::create(name: 'adm_reports', is_system: true, is_admin: true, view_name: '_navigation.tpl');

        $navigation = Navigation::select()->where('name = ?', 'adm_header')->first();

        Item::create(name: 'tokei.adm.navigation.general.main', target: '/adm/', position: 1, navigation_id: $navigation->id->value);
        Item::create(name: 'tokei.adm.navigation.events.main', target: '/adm/events/', position: 2, navigation_id: $navigation->id->value);
        Item::create(name: 'tokei.adm.navigation.reports.main', target: '/adm/reports/', position: 3, navigation_id: $navigation->id->value);

        $navigation = Navigation::select()->where('name = ?', 'adm_general')->first();

        Item::create(name: 'tokei.adm.navigation.general.location_list', target: '/adm/list-locations/', position: 1, navigation_id: (int) $navigation->id->value);
        Item::create(name: 'tokei.adm.navigation.general.location_create', target: '/adm/create-location/', position: 2, navigation_id: (int) $navigation->id->value);
        Item::create(name: 'tokei.adm.navigation.general.user_role_list', target: '/adm/list-roles/', position: 3, navigation_id: (int) $navigation->id->value);
        Item::create(name: 'tokei.adm.navigation.general.user_role_add', target: '/adm/create-role/', position: 4, navigation_id: (int) $navigation->id->value);
        Item::create(name: 'tokei.adm.navigation.general.user_list', target: '/adm/list-users/', position: 5, navigation_id: (int) $navigation->id->value);
        Item::create(name: 'tokei.adm.navigation.general.user_add', target: '/adm/create-user/', position: 6, navigation_id: (int) $navigation->id->value);

        $navigation = Navigation::select()->where('name = ?', 'adm_events')->first();

        Item::create(name: 'tokei.adm.navigation.events.list', target: '/adm/events/list/', position: 1, navigation_id: $navigation->id->value);
        Item::create(name: 'tokei.adm.navigation.events.create', target: '/adm/events/create/', position: 2, navigation_id: $navigation->id->value);
        Item::create(name: 'tokei.adm.navigation.events.create_pre_school', target: '/adm/events/create/pre-school/', position: 3, navigation_id: $navigation->id->value);
        Item::create(name: 'tokei.adm.navigation.events.create_school', target: '/adm/events/create/school/', position: 4, navigation_id: $navigation->id->value);
        Item::create(name: 'tokei.adm.navigation.events.institution_list', target: '/adm/events/list-institutions/', position: 5, navigation_id: $navigation->id->value);
        Item::create(name: 'tokei.adm.navigation.events.institution_create', target: '/adm/events/create-institution/', position: 6, navigation_id: $navigation->id->value);

        $navigation = Navigation::select()->where('name = ?', 'adm_reports')->first();

        Item::create(name: 'tokei.adm.navigation.reports.list', target: '/adm/reports/', position: 1, navigation_id: $navigation->id->value);
        Item::create(name: 'tokei.adm.navigation.reports.klr', target: '/adm/reports/klr/', position: 2, navigation_id: $navigation->id->value);

        $permissions = new Permissions();
        $user_role = new CreateRole(
            name: 'Administrator',
            permissions: $permissions->buildForInstall(),
        );
        $this->commandBus->dispatch($user_role);

        $this->console->info('Finish installing tokei, use php tempest tokei:create-admin');
    }

    #[ConsoleCommand(name: 'tokei:create-admin')]
    public function createAdmin(): void
    {
        $createUser = new CreateUser(
            username: $this->console->ask('Username ?', validation: [new IsNotEmptyString()]),
            name: '',
            surname: '',
            email: $this->console->ask('E-Mail?', validation: [new IsEmail()]),
            password: $password = $this->console->password('Password?', true, validation: [new IsPassword(12, true, true, true)]),
            password_repeat: $password,
            seal: '',
            role_id: 1,
        );

        $this->commandBus->dispatch($createUser);
    }
}
