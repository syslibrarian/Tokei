<?php

declare(strict_types=1);

namespace Tokei\Controller;

use Tempest\Http\Responses\Redirect;
use Tempest\Http\Session\Session;
use Tempest\View\View;
use Tokei\Component\Navigation\Navigation;
use Tokei\Tokei;

use function Tempest\Container\get;
use function Tempest\Support\Arr\each;
use function Tempest\View\view;

abstract class Controller
{
    protected(set) Status $status = Status::NORMAL;

    /** @var string[] */
    protected array $loadNavigation = [];

    /** @var string[] */
    protected array $viewPaths = [];

    public Session $session {
        get {
            return $this->tokei->session;
        }
    }

    public Tokei $tokei {
        get {
            return get(Tokei::class);
        }
    }

    public function __construct(
    ) {
        $this->init();
    }

    protected function init(): void
    {
        $this->beforeInit();

        each(
            $this->viewPaths,
            function (string $path, string $namespace) {
                $this->tokei->twig->getLoader()->addPath($path, $namespace);
            },
        );

        each(
            $this->loadNavigation,
            function ($name) {
                $this->add('navigation_' . $name, Navigation::get($name, true));
            },
        );

        $this->afterInit();
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

    protected function add(string $name, mixed $value): static
    {
        $this->tokei->add($name, $value);

        return $this;
    }

    public function setStatus(Status $status): static
    {
        $this->status = $status;
        $this->add('status', $this->status);

        return $this;
    }

    abstract protected function beforeInit(): void;
    abstract protected function afterInit(): void;
}
