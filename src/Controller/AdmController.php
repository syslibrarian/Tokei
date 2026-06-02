<?php

declare(strict_types=1);

namespace Tokei\Controller;

use Tokei\Command\Location\CreateLocation;
use Tokei\Command\Location\DeleteLocation;
use Tokei\Command\Location\UpdateLocation;
use Tokei\Command\Page\CreatePage;
use Tokei\Command\Page\DeletePage;
use Tokei\Command\Page\UpdatePage;
use Tokei\Command\User\CreateRole;
use Tokei\Command\User\CreateUser;
use Tokei\Command\User\DeleteRole;
use Tokei\Command\User\DeleteUser;
use Tokei\Command\User\UpdateRole;
use Tokei\Command\User\UpdateUser;
use Tokei\Model\Location\Location;
use Tokei\Model\User\Role;
use Tokei\Model\User\RoleHelper;
use Tokei\Model\User\User;
use Tokei\Tool\Pagination\Pagination;
use Tokei\Tool\User\Permissions;
use Tempest\Http\Request;
use Tempest\Http\Responses\Redirect;
use Tempest\Router\Get;
use Tempest\Router\Post;
use Tempest\Router\Prefix;
use Tempest\View\View;

use function Tokei\str\trim;
use function Tempest\CommandBus\command;
use function Tempest\Container\get;

#[Prefix('/adm')]
final class AdmController extends Controller
{
    use IsAdmin;

    protected function getSectionNavigation(): string
    {
        return 'adm_general';
    }

    protected function getBaseSlug(): string
    {
        return '/adm/';
    }

    #[Get(uri: '/')]
    public function index(): View
    {
        return $this->view('@adm/index.tpl');
    }

    #[Get(uri: '/list-roles/{?currentPage:[0-9]+}')]
    public function listRoles(int $currentPage = 1): View
    {
        $this->setActiveSlug('list-roles/');
        $roles = Role::select()->all();
        return $this->view(
            '@adm/listRole.tpl',
            roles: $roles,
            success: $this->session->get('success', false),
            id: $this->session->get('id', 0),
        );
    }

    #[Get(uri: '/create-role'), Post(uri: '/create-role')]
    public function createRole(Request $request): View|Redirect
    {
        $this->setActiveSlug('create-role/');
        /** @var \Tokei\Tool\User\Permissions $permissions */
        $permissions = get(Permissions::class);
        $createRole = new CreateRole(
            name: trim($request->get('name', '')),
            permissions: $permissions->buildForCommand($request),
        );

        $response = $this->executeCommand($createRole, $request);
        if ($response?->value instanceof Role) {
            $this->session->flash('success', true);
            $this->session->flash('id', $response->value->id->value);
            return $this->redirect('/adm/list-roles');
        }

        return $this->view(
            '@adm/createRole.tpl',
            role: $createRole,
            permissionGroups: $permissions->buildForForm(),
            errors: $this->validationParser->parsedErrors,
        );
    }

    #[Get(uri: '/update-role/{id:[0-9]+}'), Post(uri: '/update-role/{id:[0-9]+}')]
    public function updateRole(int $id, Request $request): View
    {
        $this->setActiveSlug('list-roles/');
        $role = Role::select()->where('user_role.id = ?', $id)->with('permissions')->first();

        /** @var \Tokei\Tool\User\Permissions $permissions */
        $permissions = get(Permissions::class);
        $updateRole = new UpdateRole(
            name: trim($request->get('name', $role->name)),
            permissions: $permissions->buildForCommand($request),
            model: $role,
        );

        $response = $this->executeCommand($updateRole, $request);

        if ($response !== null) {
            $role->refresh();
        }

        return $this->view(
            '@adm/updateRole.tpl',
            role: $updateRole,
            permissionGroups: $permissions->buildForForm($role),
            errors: $this->validationParser->parsedErrors,
            success: $response !== null,
        );
    }

    #[Get(uri: '/delete-role/{id:[0-9]+}')]
    public function deleteRole(int $id): Redirect
    {
        $role = Role::select()->where('id = ?', $id)->first();

        $deleteRole = new DeleteRole($role);
        command($deleteRole);

        return $this->redirect('/adm/list-roles');
    }

    #[Get(uri: '/list-users/{id:[0-9]+}')]
    public function listUsers(int $currentPage = 1): View
    {
        $this->setActiveSlug('list-users/');

        $pagination = new Pagination(
            pageNo: $currentPage,
            maxItems: User::count()->execute(),
            uri: '/adm/list-users/{no}',
        );

        $users = User::select()
            ->limit($pagination->limit)
            ->offset($pagination->offset)
            ->all();

        return $this->view(
            '@adm/listUsers.tpl',
            users: $users,
            pagination: $pagination,
        );
    }

    #[Get(uri: '/create-user'), Post(uri: '/create-user')]
    public function createUser(Request $request): View|Redirect
    {
        $this->setActiveSlug('create-user/');

        $createUser = new CreateUser(
            username: trim($request->get('username', '')),
            name: trim($request->get('name', '')),
            surname: trim($request->get('surname', '')),
            email: trim($request->get('email', '')),
            password: trim($request->get('password', '')),
            password_repeat: trim($request->get('password_repeat', '')),
            role_id: (int) $request->get('role', 0),
        );

        $response = $this->executeCommand($createUser, $request);
        if ($response?->value instanceof User) {
            $this->session->flash('success', true);
            $this->session->flash('id', $response->value->id->value);
            return $this->redirect('/adm/list-users');
        }

        return $this->view(
            '@adm/createUser.tpl',
            user: $createUser,
            errors: $this->validationParser->parsedErrors,
        );
    }

    #[Get(uri: '/update-user/{id:[0-9]+}'), Post(uri: '/update-user/{id:[0-9]+}')]
    public function updateUser(Request $request, int $id): View
    {
        $this->setActiveSlug('update-user/');

        $user = User::select()->where('id = ?', $id)->include('email')->first();
        $updateUser = new UpdateUser(
            user: $user,
            username: trim($request->get('username', $user->username)),
            name: trim($request->get('name', $user->name)),
            surname: trim($request->get('surname', '')),
            email: trim($request->get('email', '')),
            change_password: (int) $request->get('change_password', 0),
            password: trim($request->get('password', '')),
            password_repeat: trim($request->get('password_repeat', '')),
            role_id: (int) $request->get('role', $user->role_id),
        );

        return $this->view(
            '@adm/updateUser.tpl',
            user: $updateUser,
            roles: RoleHelper::getForForm(),
            errors: $this->validationParser->parsedErrors,
        );
    }

    #[Get(uri: '/delete-user/{id:[0-9]+}')]
    public function deleteUser(int $id): Redirect
    {
        $user = User::select()->where('id = ?', $id)->first();

        $deleteUser = new DeleteUser($user);
        command($deleteUser);

        return $this->redirect('/adm/list-user');
    }

    #[Get(uri: '/list-locations/{?no:[0-9]+}')]
    public function listLocations(int $currentPage = 1): View
    {
        $this->setActiveSlug('list-locations/');

        $pagination = new Pagination(
            pageNo: $currentPage,
            maxItems: Location::count()->execute(),
            uri: '/adm/list-locations/{no}'
        );

        $locations = Location::select()
            ->limit($pagination->limit)
            ->offset($pagination->offset)
            ->all();

        return $this->view(
            '@adm/listLocations.tpl',
            locations: $locations,
            pagination: $pagination,
        );
    }

    #[Get(uri: '/create-location'), Post(uri: '/create-location')]
    public function createLocation(Request $request): View|Redirect
    {
        $this->setActiveSlug('create-location/');
        $createLocation = new CreateLocation(
            name: trim($request->get('name', '')),
            seal: trim($request->get('seal', '')),
            street: trim($request->get('street', '')),
            city: trim($request->get('city', '')),
            zip_code: trim($request->get('zip_code', '')),
            fte: (float) $request->get('fte', 0),
            fte_consumed: (float) $request->get('fte_consumed', 0),
            area: (float) $request->get('area', 0),
        );

        $response = $this->executeCommand($createLocation, $request);

        if ($response?->value instanceof Location) {
            $this->session->flash('success', true);
            $this->session->flash('id', $response->value->id->value);
            return $this->redirect('/adm/list-locations/');
        }

        return $this->view(
            '@adm/createLocation.tpl',
            location: $createLocation,
            errors: $this->validationParser->parsedErrors
        );
    }

    #[Get(uri: '/update-location/{id:[0-9]+}'), Post(uri: '/update-location/{id:[0-9]+}')]
    public function updateLocation(Request $request, int $id): View
    {
        $this->setActiveSlug('list-locations/');
        $location = Location::select()->where('id = ?', $id)->first();

        $updateLocation = new UpdateLocation(
            model: $location,
            name: trim($request->get('name', $location->name)),
            seal: trim($request->get('seal', $location->seal)),
            street: trim($request->get('street', $location->street)),
            city: trim($request->get('city', $location->city)),
            zip_code: trim($request->get('zip_code', $location->zip_code)),
            fte: (float) $request->get('fte', $location->fte),
            fte_consumed: (float) $request->get('fte_consumed', $location->fte_consumed),
            area: (float) $request->get('area', $location->area),
        );

        $response = $this->executeCommand($updateLocation, $request);

        if ($response !== null) {
            $location->refresh();
        }

        return $this->view(
            '@adm/updateLocation.tpl',
            location: $updateLocation,
            errors: $this->validationParser->parsedErrors
        );
    }

    #[Get(uri: '/delete-location/{id:[0-9]+}')]
    public function deleteLocation(int $id): Redirect
    {
        $location = Location::select()->where('id = ?', $id)->first();
        $response = $this->executeCommand(new DeleteLocation($location), onPost: false);

        return $this->redirect('/adm/list-locations/');
    }
}
