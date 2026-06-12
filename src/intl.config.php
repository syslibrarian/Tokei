<?php

use Tempest\Intl\IntlConfig;
use Tempest\Intl\Locale;

return new IntlConfig(
    currentLocale: Locale::GERMAN,
    fallbackLocale: Locale::GERMAN,
);
