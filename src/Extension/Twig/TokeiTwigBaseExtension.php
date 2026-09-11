<?php

declare(strict_types=1);

namespace Tokei\Extension\Twig;

use InvalidArgumentException;
use Tokei\Tokei;
use Twig\Attribute\AsTwigFilter;
use Twig\Attribute\AsTwigFunction;
use Twig\Environment;
use Twig\Runtime\EscaperRuntime;

use function Tempest\Container\get;

final class TokeiTwigBaseExtension
{
    private static ?Tokei $tokei = null;
    private static ?string $translateBase = null;

    private static function checkTokei(Environment $env): void
    {
        if (self::$tokei === null) {
            self::$tokei = $env->getGlobals()['_tokei'] ?? get(Tokei::class);
        }
    }

    #[AsTwigFunction('hasPermission', needsEnvironment: true)]
    public static function hasPermission(Environment $env, ?string $name): bool
    {
        self::checkTokei($env);

        return self::$tokei->accessControl->hasPermission($name);
    }

    #[AsTwigFunction('canUpdate', needsEnvironment: true)]
    public static function canUpdate(Environment $env, string|object $model): bool
    {
        self::checkTokei($env);

        return self::$tokei->accessControl->canUpdate($model);
    }

    #[AsTwigFunction('canDelete', needsEnvironment: true)]
    public static function canDelete(Environment $env, string|object $model): bool
    {
        self::checkTokei($env);

        return self::$tokei->accessControl->canDelete($model);
    }

    #[
        AsTwigFilter('translateFull', needsEnvironment: true, isSafe: ['html']),
        AsTwigFunction('translateFull', needsEnvironment: true, isSafe: ['html']),
    ]
    public static function translateFull(Environment $env, string $key, mixed ...$args): string
    {
        self::checkTokei($env);

        return self::$tokei->translator->translate($key, ...$args);
    }

    #[
        AsTwigFilter('translateSecure', needsEnvironment: true, isSafe: ['html']),
        AsTwigFunction('translateSecure', needsEnvironment: true, isSafe: ['html']),
    ]
    public static function translateSecure(
        Environment $env,
        string $key,
        bool $full = false,
        string $context = 'html',
        mixed ...$args,
    ): string {
        return $env->getRuntime(EscaperRuntime::class)
            ->escape(
                $full ? self::translateFull($env, $key, ...$args) : self::translate($env, $key, ...$args),
                $context,
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
        string|object $model,
        string $context = 'public',
        string $type = 'list',
        string $appendUri = '',
        mixed ...$args
    ): string {
        self::checkTokei($env);

        return self::$tokei->routeCollectionRegistry->getUri($model, $context, $type) . self::buildUri($appendUri, ...$args);
    }

    #[AsTwigFunction('buildUri')]
    public static function buildUri(string $uri = '', mixed ...$args): string
    {
        if (str_contains($uri, '{')) {
            $parameters = [];

            preg_match_all('#{([_a-zA-Z]+[_a-zA-Z0-9]*)}#', $uri, $parameters, PREG_SET_ORDER);
            foreach ($parameters as $parameter) {
                $placeholder = $parameter[0];
                $value = $args[$parameter[1]] ?? null;

                if ($value === null) {
                    throw new InvalidArgumentException(sprintf('Missing parameter "%s"', $parameter[1]));
                }

                unset($args[$parameter[1]]);
                $uri = str_replace($placeholder, (string) $value, $uri);
            }
        }

        if (count($args) > 0) {
            $uri .= (str_contains($uri, '?') ? '&' : '?') . http_build_query($args);
        }

        return $uri;
    }

    #[
        AsTwigFilter('translate', needsEnvironment: true, isSafe: ['html']),
        AsTwigFunction('translate', needsEnvironment: true, isSafe: ['html']),
    ]
    public static function translate(Environment $env, string $key, mixed ...$args): string
    {
        return self::translateFull($env, (self::$translateBase ?? 'tokei') . '.' . $key, ...$args);
    }

    #[AsTwigFunction('translateBase')]
    public static function setTranslateBase(string $name): void
    {
        self::$translateBase = $name;
    }
}
