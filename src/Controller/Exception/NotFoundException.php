<?php

declare(strict_types=1);

namespace Tokei\Controller\Exception;

class NotFoundException extends \Exception
{
    public function __construct(
        protected(set) string $modelClass = '',
        protected(set) string $baseSlug = '',
    ) {
        parent::__construct();
    }
}
