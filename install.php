<?php
declare(strict_types=1);

// This file is only for fast dev.
require_once __DIR__ . '/vendor/autoload.php';


use Tempest\DateTime\Timestamp;
use Tokei\Model\Location\Location;
use Tokei\Model\Navigation\Navigation;
use Tokei\Model\Navigation\Item;
use Tokei\Tool\Installer\Database\CreateKlrMonthTable;
use Tokei\Tool\Installer\Database\EventCreateTable;
use Tokei\Tool\Installer\Database\InstitutionCreateTable;
use Tokei\Tool\Installer\Database\LocationCreateTable;
use Tokei\Tool\Installer\Database\NavigationCreateTable;
use Tokei\Tool\Installer\Database\NavigationItemCreateTable;
use Tokei\Tool\Installer\Database\PageCreateTable;
use Tokei\Tool\Installer\Database\ReportCreateTable;
use Tokei\Tool\Installer\Database\UserCreateTable;
use Tokei\Tool\Installer\Database\UserPermissionCreateTable;
use Tokei\Tool\Installer\Database\UserRoleCreateTable;
use Tempest\Console\ConsoleApplication;

$app = ConsoleApplication::boot(__DIR__ . '/');

// navigation
$navigation = new NavigationCreateTable();
$navigation->execute();

$navigation_item = new NavigationItemCreateTable();
$navigation_item->execute();

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

// User
$userRole = new UserRoleCreateTable();
$userRole->execute();

$permissions = new UserPermissionCreateTable();
$permissions->execute();

$user = new UserCreateTable();
$user->execute();

// locations
$location = new LocationCreateTable();
$location->execute();

Location::create(
    name: 'BZB',
    seal: '713',
    klr_code: 'BZB',
    street: 'Götzstr. 8/10/12',
    city: 'Berlin',
    postal_code: '12099',
    fte: 0,
    fte_consumed: 0,
    area: 0,
    created: TimeStamp::now()->getSeconds()
);

// institutions
$institution = new InstitutionCreateTable();
$institution->execute();

// events
$events = new EventCreateTable();
$events->execute();

// reports
$report = new ReportCreateTable();
$report->execute();

// klr
$klrMonth = new CreateKlrMonthTable();
$klrMonth->execute();