<?php

declare(strict_types=1);

namespace Tokei\Extension\Twig;

use Tokei\Tokei;
use Twig\Attribute\AsTwigFilter;
use Twig\Attribute\AsTwigFunction;
use Twig\Environment;

use Twig\Runtime\EscaperRuntime;
use function Tempest\Container\get;

final class TokeiTwigBaseExtension
{
    protected static ?Tokei $tokei = null;
    protected static ?string $translateBase = null;

    #[
        AsTwigFilter('translateFull', needsEnvironment: true, isSafe: ['html']),
        AsTwigFunction('translateFull', needsEnvironment: true, isSafe: ['html'])
    ]
    public static function translateFull(Environment $env, string $key, mixed ...$args): string
    {
        if (self::$tokei === null) {
            self::$tokei = $env->getGlobals()['_tokei'] ?? get(Tokei::class);
        }

        return self::$tokei->translator->translate($key, ...$args);
    }

    #[
        AsTwigFilter('translateSecure', needsEnvironment: true, isSafe: ['html']),
        AsTwigFunction('translateSecure', needsEnvironment: true, isSafe: ['html'])
    ]
    public static function translateSecure(
        Environment $env,
        string $key,
        bool $full = false,
        string $context = 'html',
        mixed ...$args
    ): string
    {
        return $env->getRuntime(EscaperRuntime::class)
            ->escape(
                ($full) ? self::translateFull($env, $key, ...$args) : self::translate($env, $key, ...$args),
                $context
            );
    }

    #[AsTwigFunction('note', needsEnvironment: true, isSafe: ['html'])]
    public static function note(Environment $env, string $message, string $class = 'info', mixed ...$args): string
    {
        if (isset($args['errors']) && is_array($args['errors'])) {
            return $env->render('_noteError.tpl', ['message' => $message, 'errors' => $args['errors']]);
        }

        return $env->render('_note.tpl', ['message' => $message, 'class' => NoteTypes::get($class)]);
    }

    #[AsTwigFunction('getUri', needsEnvironment: true)]
    public static function getUri(
        Environment $env,
        bool $withBase = true,
        bool $withCurrent = true,
        string $uri = '',
        mixed ... $parts
    ): string
    {
        if (self::$tokei === null) {
            self::$tokei = $env->getGlobals()['_tokei'] ?? get(Tokei::class);
        }
        return self::$tokei->getUri($withBase, $withCurrent, $uri, ... $parts);
    }

    #[
        AsTwigFilter('translate', needsEnvironment: true, isSafe: ['html']),
        AsTwigFunction('translate', needsEnvironment: true, isSafe: ['html'])
    ]
    public static function translate(Environment $env, string $key, mixed ...$args): string
    {
        return self::translateFull($env,(self::$translateBase ?? 'tokei') . '.' . $key, ...$args);
    }

    #[AsTwigFunction('translateBase')]
    public static function setTranslateBase(string $name): void
    {
        self::$translateBase = $name;
    }
}
