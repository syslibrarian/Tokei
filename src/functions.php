<?php

declare(strict_types=1);

namespace Tokei\str {
    function trim(string $str): string
    {
        $boundaryCharacters = '\p{Cc}\p{Zs}\p{Zl}\p{Zp}\s\x{202E}\x{200B}';
        $fullStringCharacters = $boundaryCharacters . '\p{Cf}\p{Z}\x{2800}\x{3164}\x{FFA0}\x{1D159}\x{1D173}-\x{1D17A}';

        $trimmed = \preg_replace("/^[{$boundaryCharacters}]+/u", '', $str);
        if ($trimmed === null) {
            return $str;
        }

        $trimmed = \preg_replace("/[{$boundaryCharacters}]+$/u", '', $trimmed);
        if ($trimmed === null) {
            return $str;
        }

        if (\preg_match("/^[{$fullStringCharacters}]+$/u", $trimmed)) {
            return '';
        }

        return $trimmed;
    }
}
