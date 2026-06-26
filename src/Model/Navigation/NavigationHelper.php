<?php

declare(strict_types=1);

namespace Tokei\Model\Navigation;

final class NavigationHelper
{
    public static function forTemplate(): array
    {
        $navigations = Navigation::select()
            ->where('is_admin = ?', false)
            ->all();

        $options = [];
        foreach ($navigations as $navigation) {
            $options[] = ['name' => $navigation->name, 'value' => $navigation->id->value];
        }

        return $options;
    }
}
