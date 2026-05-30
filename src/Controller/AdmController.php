<?php

declare(strict_types=1);

namespace Tokei\Controller;

use Tokei\Command\Page\CreatePage;
use Tokei\Command\Page\DeletePage;
use Tokei\Command\Page\UpdatePage;
use Tokei\Command\User\CreateRole;
use Tokei\Command\User\CreateUser;
use Tokei\Command\User\DeleteRole;
use Tokei\Command\User\DeleteUser;
use Tokei\Command\User\UpdateRole;
use Tokei\Command\User\UpdateUser;
use Tokei\Model\Navigation\NavigationHelper;
use Tokei\Model\Page\Page;
use Tokei\Model\Page\PageHelper;
use Tokei\Model\PublicationStatus;
use Tokei\Model\User\Role;
use Tokei\Model\User\RoleHelper;
use Tokei\Model\User\User;
use Tokei\Tool\Pagination\Pagination;
use Tokei\Tool\User\Permissions;
use Tempest\Http\Method;
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

        $response = $this->sendCommand($createRole, $request);
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

        $response = $this->sendCommand($updateRole, $request);

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

        $response = $this->sendCommand($createUser, $request);
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
}
