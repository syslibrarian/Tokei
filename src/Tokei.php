<?php

declare(strict_types=1);

namespace Tokei;

use Tempest\Container\Singleton;
use Tempest\DateTime\DateTime;
use Tempest\Http\Session\Session;
use Tempest\Intl\Translator;
use Tokei\Component\Access\AccessControl;
use Tokei\Extension\Twig\TokeiTwigBaseExtension;
use Tokei\Tool\Model\RouteCollectionRegistry;
use Twig\Environment;
use Twig\Extension\AttributeExtension;
use Twig\Extension\CoreExtension;

#[Singleton]
final class Tokei
{
    public const string VERSION = '0.5.0';
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
        protected(set) RouteCollectionRegistry $routeCollectionRegistry,
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
