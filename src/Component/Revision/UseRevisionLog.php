<?php

declare(strict_types=1);

namespace Tokei\Component\Revision;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class UseRevisionLog
{
    public function __construct(
        public string $historyTemplate = '_revisionHistory.tpl',
        public string $detailTemplate = '_revisionDetail.tpl',
    ) {}
}
