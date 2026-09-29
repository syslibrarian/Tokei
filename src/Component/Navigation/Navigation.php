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

    private(set) string $activeItem = '';

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
        return $this->twig->render($this->navigation->view_name, ['navigation' => $this->navigation, 'activeItem' => $this->activeItem]);
    }

    public function setActiveItem(string $name): static
    {
        $this->activeItem = $name;

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
