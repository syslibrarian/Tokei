<?php

declare(strict_types=1);

namespace Tokei\Controller;

use Tempest\Http\Responses\Redirect;
use Tempest\Http\Session\Session;
use Tempest\View\View;
use Tokei\Component\Navigation\Navigation;
use Tokei\Tokei;

use function Tempest\Support\Arr\each;
use function Tempest\View\view;

abstract class Controller
{
    /** @var string[] */
    protected array $loadNavigation = [];

    /** @var string[] */
    protected array $viewPaths = [];

    public Session $session {
        get {
            return $this->tokei->session;
        }
    }

    public function __construct(
        protected(set) Tokei $tokei,
    ) {
        $this->extend();
    }

    protected function extend(): void
    {
        each(
            $this->viewPaths,
            function (string $path, string $namespace) {
                $this->tokei->twig->getLoader()->addPath($path, $namespace);
            },
        );

        each(
            $this->loadNavigation,
            function ($name) {
                $this->tokei->add('navigation_' . $name, Navigation::get($name, true));
            },
        );
    }

    protected function registerNavigation(string $name): void
    {
        $this->loadNavigation[] = $name;
    }

    protected function registerViewPath(string $namespace, string $path): void
    {
        $this->viewPaths[$namespace] = $path;
    }

    protected function view(string $templateName, mixed ...$data): View
    {
        return view($templateName, ...$data);
    }

    protected function redirect(string $to): Redirect
    {
        return new Redirect($to);
    }
}
