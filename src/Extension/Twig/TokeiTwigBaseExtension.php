<?php

declare(strict_types=1);

namespace Tokei\Extension\Twig;

use Tokei\Tokei;
use Twig\Attribute\AsTwigFilter;
use Twig\Attribute\AsTwigFunction;
use Twig\Environment;

use function Tempest\Container\get;

final class TokeiTwigBaseExtension
{
    protected static ?Tokei $tokei = null;

    #[AsTwigFilter('translate', isSafe: ['html']), AsTwigFunction('translate', isSafe: ['html'])]
    public static function translate(string $key, mixed ...$args): string
    {
        if ($args) {
            foreach ($args as $index => $arg) {
                $key .= '[' . $index . '] => ' . $arg;
            }
        }
        return $key;
    }

    #[AsTwigFunction('note', needsEnvironment: true, isSafe: ['html'])]
    public static function note(Environment $env, string $message, string $class = 'info'): string
    {
        return $env->render('_note.tpl', ['message' => $message, 'class' => NoteTypes::get($class)]);
    }

    #[AsTwigFunction('getUri', needsEnvironment: true)]
    public static function getUri(Environment $env, bool $withBase = true, bool $withCurrent = true, string $uri = '', ... $parts): string
    {
        if (static::$tokei === null) {
            static::$tokei = $env->getGlobals()['_tokei'] ?? get(Tokei::class);
        }
        return static::$tokei->getUri($withBase, $withCurrent, $uri, ... $parts);
    }
}
