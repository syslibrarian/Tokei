<?php

declare(strict_types=1);

namespace Tokei\Extension\Twig;

use Tempest\DateTime\DateTime;
use Tokei\Extension\DateTime\DateTimeTool;
use Tokei\Extension\DateTime\DefaultDateTime;
use Tokei\Tokei;
use Twig\Attribute\AsTwigFilter;
use Twig\Attribute\AsTwigFunction;
use Twig\Environment;
use Twig\Runtime\EscaperRuntime;

use function Tempest\Container\get;
use function Tokei\misc\buildUri;
use function Tokei\misc\getUri;

final class TokeiTwigBaseExtension
{
    private static ?Tokei $tokei = null;
    private static ?string $translateBase = null;

    private static function checkTokei(): void
    {
        if (self::$tokei === null) {
            self::$tokei = get(Tokei::class);
        }
    }

    #[AsTwigFunction('hasPermission')]
    public static function hasPermission(?string $name): bool
    {
        self::checkTokei();
        return self::$tokei->accessControl->hasPermission($name);
    }

    #[AsTwigFunction('canUpdate')]
    public static function canUpdate(string|object $model): bool
    {
        self::checkTokei();

        return self::$tokei->accessControl->canUpdate($model);
    }

    #[AsTwigFunction('canDelete')]
    public static function canDelete(string|object $model): bool
    {
        self::checkTokei();

        return self::$tokei->accessControl->canDelete($model);
    }

    #[AsTwigFunction('note', needsEnvironment: true, isSafe: ['html'])]
    public static function note(Environment $env, string $message, string $class = 'info', mixed ...$args): string
    {
        if (isset($args['errors']) && is_array($args['errors'])) {
            return $env->render('_noteError.tpl', ['message' => $message, 'errors' => $args['errors']]);
        }

        return $env->render('_note.tpl', ['message' => $message, 'class' => NoteTypes::get($class)]);
    }

    #[AsTwigFunction('getUri')]
    public static function getUri(
        string|object $model,
        string $context = 'public',
        string $type = 'list',
        string $appendUri = '',
        mixed ...$args
    ): string {
        return getUri($model, $context, $type, $appendUri, ...$args);
    }

    #[AsTwigFunction('buildUri')]
    public static function buildUri(string $uri = '', mixed ...$args): string
    {
        return buildUri($uri, ...$args);
    }

    #[
        AsTwigFilter('translateFull', isSafe: ['html']),
        AsTwigFunction('translateFull', isSafe: ['html']),
    ]
    public static function translateFull(string $key, mixed ...$args): string
    {
        self::checkTokei();

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
                $full ? self::translateFull($key, ...$args) : self::translate($key, ...$args),
                $context,
            );
    }

    #[
        AsTwigFilter('translate', isSafe: ['html']),
        AsTwigFunction('translate', isSafe: ['html']),
    ]
    public static function translate(string $key, mixed ...$args): string
    {
        $key = (self::$translateBase ?? 'tokei') . '.' . $key;
        return self::translateFull($key, ...$args);
    }

    #[AsTwigFunction('translateBase')]
    public static function setTranslateBase(string $name): void
    {
        self::$translateBase = $name;
    }

    #[AsTwigFunction('dateLong'), AsTwigFilter('dateLong')]
    public static function dateLong(int $timestamp, bool $useUTC = false): string
    {
        return(get(DateTimeTool::class)->formatLong($timestamp, $useUTC));
    }

    #[AsTwigFunction('dateShort'), AsTwigFilter('dateShort')]
    public static function dateShort(int $timestamp, bool $useUTC = false): string
    {
        return(get(DateTimeTool::class)->formatShort($timestamp, $useUTC));
    }

    #[AsTwigFunction('dateTime'), AsTwigFilter('dateTime')]
    public static function dateTime(int $timestamp, bool $useUTC = false): string
    {
        return(get(DateTimeTool::class)->formatTime($timestamp, $useUTC));
    }

    #[AsTwigFunction('dateInput'), AsTwigFilter('dateInput')]
    public static function dateInput(int $timestamp, bool $useUTC = false): string
    {
        return(get(DateTimeTool::class)->formatForInput($timestamp, $useUTC));
    }

    #[AsTwigFunction('dateFormat'), AsTwigFilter('dateFormat')]
    public static function dateFormat(int $timestamp, string $format, bool $useUTC = false): string
    {
        return(get(DateTimeTool::class)->format($timestamp, $format, $useUTC));
    }
}
