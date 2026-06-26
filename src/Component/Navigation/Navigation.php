<?php

declare(strict_types=1);

namespace Tokei\Component\Navigation;

use Tokei\Model\Navigation\Navigation as NavigationModel;
use Twig\Environment;

use function Tempest\Container\get;

final class Navigation
{
    /** @var Navigation[] $loadedNavigation */
    private static array $loadedNavigation = [];

    private(set) NavigationModel $navigation;
    private Environment $twig {
        get {
            return get(Environment::class);
        }
    }

    private(set) string $activeTarget = '';

    private function __construct(
        protected string $name,
    ) {
        $this->loadNavigation();
    }

    private function loadNavigation(): void
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

    public static function get(string $name, bool $load = false): ?self
    {
        if (! isset(self::$loadedNavigation[$name]) && $load) {
            self::$loadedNavigation[$name] = new Navigation($name);
        }

        return self::$loadedNavigation[$name] ?? null;
    }
}
