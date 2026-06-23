<?php

declare(strict_types=1);

namespace Tokei\Controller;

use Tempest\Auth\Authentication\Authenticator;
use Tempest\Cryptography\Password\PasswordHasher;
use Tempest\Http\Method;
use Tempest\Http\Request;
use Tempest\Http\Responses\Redirect;
use Tempest\Router\Get;
use Tempest\Router\Post;
use Tempest\View\View;
use Tokei\Model\User\User;

final class MainController extends Controller
{
    use IsPublic;

    #[Get(uri: '/')]
    public function index(): View
    {
        return $this->view('base.tpl');
    }

    #[Get(uri: '/login/'), Post(uri: '/login/')]
    public function login(Request $request, Authenticator $authenticator, PasswordHasher $hasher): View|Redirect
    {
        $errors = [];
        $username = trim($request->get('username', ''));
        $password = trim($request->get('password', ''));

        if ($request->method === Method::POST) {
            $user = User::select()->where('username LIKE ?', $username)->include('password')->with('role', 'role.permissions')->first();

            if ($user !== null && $hasher->verify($password, $user->password)) {
                $authenticator->authenticate($user);
                return $this->redirect('/adm/');
            }

            $errors['username'] = 'tokei.main.login_user_not_found';
            $errors['password'] = 'tokei.main.login_user_not_found';
        }

        return $this->view(
            'login.tpl',
            username: $username,
            errors: $errors,
        );
    }

    #[Get(uri: '/logout/')]
    public function logout(Authenticator $authenticator): Redirect
    {
        $authenticator->deauthenticate();

        return $this->redirect('/');
    }
}
