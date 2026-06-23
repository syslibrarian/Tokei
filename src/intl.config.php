<?php

declare(strict_types=1);

use Tempest\Intl\IntlConfig;
use Tempest\Intl\Locale;

return new IntlConfig(
    currentLocale: Locale::GERMAN,
    fallbackLocale: Locale::GERMAN,
);
