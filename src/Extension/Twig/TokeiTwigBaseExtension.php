<?php

declare(strict_types=1);

namespace Tokei\Extension\Twig;

use Twig\Attribute\AsTwigFilter;
use Twig\Attribute\AsTwigFunction;
use Twig\Environment;

class TokeiTwigBaseExtension
{
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
}
