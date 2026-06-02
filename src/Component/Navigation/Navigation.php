<?php

declare(strict_types=1);

namespace Tokei\Component\Navigation;

use Tokei\Model\Navigation\Navigation as NavigationModel;
use Twig\Environment;

use function Tempest\Container\get;

class Navigation
{
    /** @var Navigation[] $loadedNavigation */
    protected static array $loadedNavigation = [];

    protected(set) NavigationModel $navigation;
    protected Environment $twig {
        get {
            return get(Environment::class);
        }
    }

    protected(set) string $activeTarget = '';

    protected function __construct(
        protected string $name,
    ) {
        $this->loadNavigation();
    }

    protected function loadNavigation(): void
    {
        $this->navigation = NavigationModel::select()
            ->where('navigation.name = ?', $this->name)
            ->with('items')
            ->orderBy('position')
            ->first();
    }

    public function __toString(): string
    {
        return $this->twig->render($this->navigation->view_name, ['navigation' => $this->navigation, 'activeTarget' => $this->activeTarget]);
    }

    public function setActiveTarget(string $slug): static
    {
        $this->activeTarget = $slug;

        return $this;
    }

    public static function get(string $name, bool $load = false): ?static
    {
        if (! isset(static::$loadedNavigation[$name]) && $load) {
            static::$loadedNavigation[$name] = new Navigation($name);
        }

        return static::$loadedNavigation[$name] ?? null;
    }
}
