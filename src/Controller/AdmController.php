<?php

declare(strict_types=1);

namespace Tokei\Controller;

use Tempest\DateTime\DateTime;
use Tempest\Http\Request;
use Tempest\Http\Responses\Redirect;
use Tempest\Log\Logger;
use Tempest\Router\Get;
use Tempest\Router\Post;
use Tempest\Router\Prefix;
use Tempest\Router\WithMiddleware;
use Tempest\View\View;
use Tokei\Command\Klr\CreateMonths;
use Tokei\Command\Location\CreateLocation;
use Tokei\Command\Location\CreateReports;
use Tokei\Command\Location\DeleteLocation;
use Tokei\Command\Location\UpdateLocation;
use Tokei\Command\User\CreateRole;
use Tokei\Command\User\CreateUser;
use Tokei\Command\User\DeleteRole;
use Tokei\Command\User\DeleteUser;
use Tokei\Command\User\UpdateRole;
use Tokei\Command\User\UpdateUser;
use Tokei\Component\Access\AccessContext;
use Tokei\Component\Access\IsAuthenticated;
use Tokei\Model\Event\EventHelper;
use Tokei\Model\Location\Location;
use Tokei\Model\Location\LocationHelper;
use Tokei\Model\Location\MonthlyReport;
use Tokei\Model\Location\ReportHelper;
use Tokei\Model\User\Role;
use Tokei\Model\User\RoleHelper;
use Tokei\Model\User\User;
use Tokei\Tool\Pagination\Pagination;
use Tokei\Tool\Role\Permissions;

use function Tempest\CommandBus\command;
use function Tempest\Container\get;
use function Tokei\str\trim;

#[Prefix('/adm'), WithMiddleware(IsAuthenticated::class)]
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
        $this->checkModel(Role::class);

        $this->setActiveSlug('create-role/');
        /** @var \Tokei\Tool\Role\Permissions $permissions */
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
        $this->checkModel($role);

        /** @var \Tokei\Tool\Role\Permissions $permissions */
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
        $role = $this->getModel($id, Role::class, AccessContext::DELETE);

        $deleteRole = new DeleteRole($role);
        command($deleteRole);

        return $this->redirect('/adm/list-roles');
    }

    #[Get(uri: '/list-users/{?currentPage:[0-9]+}')]
    public function listUsers(int $currentPage = 1): View
    {
        $this->setActiveSlug('list-users/');

        $userId = $this->session->get('created_id', null);
        $user = $userId !== null ? User::select()->where('id = ?', $userId)->first() : null;

        $pagination = new Pagination(
            pageNo: $currentPage,
            maxItems: User::count()->execute(),
            uri: '/adm/list-users/{no}',
        );

        $users = User::select()
            ->with('role')
            ->limit($pagination->limit)
            ->offset($pagination->offset)
            ->all();

        return $this->view(
            '@adm/listUser.tpl',
            success: $this->session->get('success', false),
            users: $users,
            user: $user,
            pagination: $pagination,
        );
    }

    #[Get(uri: '/create-user'), Post(uri: '/create-user')]
    public function createUser(Request $request): View|Redirect
    {
        $this->checkModel(User::class);
        $this->setActiveSlug('create-user/');

        $createUser = new CreateUser(
            username: trim($request->get('username', '')),
            name: trim($request->get('name', '')),
            surname: trim($request->get('surname', '')),
            email: trim($request->get('email', '')),
            password: trim($request->get('password', '')),
            password_repeat: trim($request->get('passwordRepeat', '')),
            seal: trim($request->get('seal', '')),
            role_id: (int) $request->get('role', 0),
        );

        $response = $this->executeCommand($createUser, $request);
        if ($response?->value instanceof User) {
            $this->session->flash('success', true);
            $this->session->flash('created_id', $response->value->id->value);
            return $this->redirect('/adm/list-users');
        }

        return $this->view(
            '@adm/createUser.tpl',
            user: $createUser,
            roles: RoleHelper::getForForm(),
            locations: LocationHelper::getLocationsForForm(true),
            errors: $this->validationParser->parsedErrors,
        );
    }

    #[Get(uri: '/update-user/{id:[0-9]+}'), Post(uri: '/update-user/{id:[0-9]+}')]
    public function updateUser(Request $request, int $id): View
    {
        $this->setActiveSlug('update-user/');

        $user = User::select()->where('id = ?', $id)->include('email')->first();
        $this->checkModel($user);

        $updateUser = new UpdateUser(
            model: $user,
            username: trim($request->get('username', $user->username)),
            name: trim($request->get('name', $user->name)),
            surname: trim($request->get('surname', '')),
            email: trim($request->get('email', $user->email)),
            change_password: (int) $request->get('change_password', 0),
            password: trim($request->get('password', '')),
            password_repeat: trim($request->get('passwordRepeat', '')),
            seal: trim($request->get('seal', $user->seal)),
            role_id: (int) $request->get('role', $user->role_id),
        );

        $response = $this->executeCommand($updateUser, $request);

        return $this->view(
            '@adm/updateUser.tpl',
            user: $updateUser,
            roles: RoleHelper::getForForm(),
            locations: LocationHelper::getLocationsForForm(true),
            errors: $this->validationParser->parsedErrors,
            success: $response?->value === true,
        );
    }

    #[Get(uri: '/delete-user/{id:[0-9]+}')]
    public function deleteUser(int $id): Redirect
    {
        $user = $this->getModel($id, User::class, AccessContext::DELETE);

        if ($this->accessControl->isSelf($user)) {
            $this->session->flash('error', 'no_self_delete');
        } else {
            $deleteUser = new DeleteUser($user);
            command($deleteUser);
        }

        return $this->redirect('/adm/list-user');
    }

    #[Get(uri: '/show-location/{seal:[0-9]{3}[a-z]?}/')]
    public function showLocation(string $seal): View
    {
        $location = $this->getBySeal($seal, Location::class);
        $events = EventHelper::getEventsByPeriod($location->seal);
        $reports = ReportHelper::getFor($location->seal);
        $report = ReportHelper::getLastFor($location->seal);

        return $this->view(
            '@adm/showLocation.tpl',
            location: $location,
            events: $events,
            reports: $reports,
            lastReport: $report,
        );
    }

    #[Get(uri: '/list-locations/{?no:[0-9]+}')]
    public function listLocations(int $currentPage = 1): View
    {
        $this->setActiveSlug('list-locations/');

        $pagination = new Pagination(
            pageNo: $currentPage,
            maxItems: Location::count()->execute(),
            uri: '/adm/list-locations/{no}',
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
        $this->checkModel(Location::class);
        $this->setActiveSlug('create-location/');
        $createLocation = new CreateLocation(
            name: trim($request->get('name', '')),
            seal: trim($request->get('seal', '')),
            street: trim($request->get('street', '')),
            city: trim($request->get('city', '')),
            postal_code: trim($request->get('postal_code', '')),
            fte: (float) $request->get('fte', 0),
            fte_consumed: (float) $request->get('fte_consumed', 0),
            area: (float) $request->get('area', 0),
            klrCode: trim($request->get('klr_code', '')),
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
            errors: $this->validationParser->parsedErrors,
        );
    }

    #[Get(uri: '/update-location/{id:[0-9]+}'), Post(uri: '/update-location/{id:[0-9]+}')]
    public function updateLocation(Request $request, int $id): View
    {
        $this->setActiveSlug('list-locations/');
        $location = $this->getModel($id, Location::class, AccessContext::UPDATE);

        $updateLocation = new UpdateLocation(
            model: $location,
            name: trim($request->get('name', $location->name)),
            seal: trim($request->get('seal', $location->seal)),
            street: trim($request->get('street', $location->street)),
            city: trim($request->get('city', $location->city)),
            postal_code: trim($request->get('postal_code', $location->postal_code)),
            fte: (float) $request->get('fte', $location->fte),
            fte_consumed: (float) $request->get('fte_consumed', $location->fte_consumed),
            area: (float) $request->get('area', $location->area),
            klrCode: trim($request->get('klrCode', $location->klr_code)),
        );

        $response = $this->executeCommand($updateLocation, $request);

        if ($response !== null) {
            $location->refresh();
        }

        return $this->view(
            '@adm/updateLocation.tpl',
            location: $updateLocation,
            errors: $this->validationParser->parsedErrors,
            success: $response !== null,
        );
    }

    #[Get(uri: '/delete-location/{id:[0-9]+}')]
    public function deleteLocation(int $id): Redirect
    {
        $location = $this->getModel($id, Location::class, AccessContext::DELETE);
        $this->executeCommand(new DeleteLocation($location), onPost: false);

        return $this->redirect('/adm/list-locations/');
    }

    #[Get(uri: '/create-reports/{?year:[0-9]{4}}')]
    public function createReports(Request $request, int $year = 0): Redirect
    {
        $this->checkModel(MonthlyReport::class);
        $validYear = fn (int $year) => $year >= 2026; // for later - get installation year for checkup.
        $year = $validYear($year) ? $year : DateTime::now()->getYear();

        try {
            $reportCommand = new CreateReports($year);
            command($reportCommand); // fire and foreget.

            $klrCommand = new CreateMonths($year);
            command($klrCommand); // fire and forget.
        } catch (\Throwable $e) {
            get(Logger::class)->error($e);
            $this->session->flash('error', 'create_reports');
        }

        return $this->redirect('/adm/');
    }
}
