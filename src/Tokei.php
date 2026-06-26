<?php

declare(strict_types=1);

namespace Tokei;

use Tempest\Container\Singleton;
use Tempest\DateTime\DateTime;
use Tempest\Http\Session\Session;
use Tempest\Intl\Translator;
use Tokei\Component\Access\AccessControl;
use Tokei\Extension\Twig\TokeiTwigBaseExtension;
use Twig\Environment;
use Twig\Extension\AttributeExtension;
use Twig\Extension\CoreExtension;

#[Singleton]
final class Tokei
{
    public const string VERSION = '0.1.0';
    public const string NAME = 'Tokei';

    /**
     * @var array<string,mixed>
     */
    public array $data = [];

    public function __construct(
        protected(set) Environment $twig,
        protected(set) AccessControl $accessControl,
        protected(set) Session $session,
        protected(set) Translator $translator,
    ) {
        $this->extendTwig();
        $this->add('year', DateTime::now()->getYear());
        $this->add('month', DateTime::now()->getMonth());
        $this->add('time', DateTime::now()->getTimestamp()->getSeconds());
    }

    private function extendTwig(): void
    {
        $this->twig->addExtension(new AttributeExtension(TokeiTwigBaseExtension::class));
        $this->twig->addGlobal('_tokei', $this);

        // format number
        $this->twig->getExtension(CoreExtension::class)->setNumberFormat(
            decimal: 0,
            decimalPoint: $this->translator->translate('tokei.number.decimal'),
            thousandSep: $this->translator->translate('tokei.number.thousands'),
        );
    }

    /**
     * @param bool $withBase
     * @param bool $withCurrent
     * @param string $uri
     * @param mixed ...$parts
     * @return string
     */
    public function getUri(bool $withBase = true, bool $withCurrent = true, string $uri = '', ...$parts): string
    {
        $base = $this->data['route_base'] ?? '';
        $current = $this->data['route_current'] ?? '';
        $uri = str_starts_with($uri, '/') ? substr($uri, 1) : $uri;

        $uri = ($withBase ? $base : '/') . ($withCurrent ? $current : '') . ($uri !== '' && ! str_ends_with($uri, '/') ? $uri . '/' : $uri);

        foreach ($parts as $part) {
            $uri .= $part . '/';
        }

        return $uri;
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
