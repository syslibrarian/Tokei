<?php
declare(strict_types=1);

// This file is only for fast dev.
require_once __DIR__ . '/vendor/autoload.php';

use Tokei\Model\Navigation\Navigation;
use Tokei\Model\Navigation\Item;
use Tokei\Tool\Installer\Database\NavigationCreateTable;
use Tokei\Tool\Installer\Database\NavigationItemCreateTable;
use Tokei\Tool\Installer\Database\PageCreateTable;
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

$navigation = Navigation::select()->where('name = ?', 'adm_header')->first();

Item::create(name: 'adm.navigation.general', target: '/adm/', position: 1, navigation_id: $navigation->id->value);

$navigation = Navigation::select()->where('name = ?', 'adm_general')->first();

Item::create(name: 'adm.user_role_list', target: '/adm/list-roles/', position: 3, navigation_id: (int) $navigation->id->value);
Item::create(name: 'adm.user_role_add', target: '/adm/create-role/', position: 4, navigation_id: (int) $navigation->id->value);
Item::create(name: 'adm.user_list', target: '/adm/list-users/', position: 5, navigation_id: (int) $navigation->id->value);
Item::create(name: 'adm.user_add', target: '/adm/create-user/', position: 6, navigation_id: (int) $navigation->id->value);

// User
$userRole = new UserRoleCreateTable();
$userRole->execute();

$permissions = new UserPermissionCreateTable();
$permissions->execute();

$user = new UserCreateTable();
$user->execute();
