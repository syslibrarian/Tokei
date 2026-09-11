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
    protected(set) Status $status = Status::NORMAL;

    /** @var string[] */
    protected array $loadNavigation = [];

    /** @var string[] */
    protected array $viewPaths = [];

    /** @var array<string, mixed> */
    protected(set) array $data = [];

    public Session $session {
        get {
            return $this->tokei->session;
        }
    }

    public function __construct(
        protected(set) Tokei $tokei,
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

        $navigation = [];
        each(
            $this->loadNavigation,
            function ($name, $key) use (&$navigation) {
                $navigation[$key] = Navigation::get($name, true);
            },
        );
        $this->register('navigation', $navigation);

        $this->afterInit();
    }

    protected function registerNavigation(string $name, ?string $as = null): void
    {
        if ($as !== null) {
            $this->loadNavigation[$as] = $name;
            return;
        }

        $this->loadNavigation[$name] = $name;
    }

    protected function registerViewPath(string $namespace, string $path): void
    {
        $this->viewPaths[$namespace] = $path;
    }

    protected function view(string $templateName, mixed ...$data): View
    {
        $this->tokei->twig->addGlobal('_page', $this->data);
        $this->tokei->twig->addGlobal('_status', $this->status);
        return view($templateName, ...$data);
    }

    protected function redirect(string $to): Redirect
    {
        return new Redirect($to);
    }

    protected function register(string $name, mixed $value): static
    {
        if (is_array($value) && is_array($this->data[$name])) {
            $this->data[$name] = array_merge($this->data[$name], $value);
            return $this;
        }

        $this->data[$name] = $value;

        return $this;
    }

    public function setStatus(Status $status): static
    {
        $this->status = $status;
        return $this;
    }

    abstract protected function beforeInit(): void;

    abstract protected function afterInit(): void;
}
