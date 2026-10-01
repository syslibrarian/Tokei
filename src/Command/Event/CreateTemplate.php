<?php

declare(strict_types=1);

namespace Tokei\Command\Event;

use Tokei\Command\IsResettable;
use Tokei\Command\Resettable;
use Tokei\Model\Event\Template;

final class CreateTemplate implements Resettable
{
    use IsResettable;

    protected(set) string $modelClass = Template::class;

    public function __construct(
        public string $name = '',
        public string $description = '',
        public int $length = 0,
    ) {}
}
