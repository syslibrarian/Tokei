<?php

declare(strict_types=1);

namespace Tokei;

use Tokei\Extension\Twig\TokeiTwigBaseExtension;
use Tempest\Container\Singleton;
use Tempest\Http\Session\Session;
use Twig\Environment;
use Twig\Extension\AttributeExtension;

#[Singleton]
final class Tokei
{
    public const string VERSION = '0.1.0';
    public const string NAME = 'Tokei';

    public array $data = [];

    public function __construct(
        protected(set) Environment $twig,
        protected(set) Session $session,
    ) {
        $this->extendTwig();
    }

    protected function extendTwig(): void
    {
        $this->twig->addExtension(new AttributeExtension(TokeiTwigBaseExtension::class));
        $this->twig->addGlobal('_tokei', $this);
    }

    public function add(string $key, mixed $value): static
    {
        $this->data[$key] = $value;
        return $this;
    }

    public function __set(string $key, mixed $value): void
    {
        $this->add($key, $value);
    }

    public function __get(string $key): mixed
    {
        if ($key === 'version') {
            return self::VERSION;
        }

        if ($key === 'name') {
            return self::NAME;
        }

        return $this->data[$key] ?? null;
    }

    public function __isset(string $key): bool
    {
        return $key === 'version' || $key === 'name' || isset($this->data[$key]);
    }
}
