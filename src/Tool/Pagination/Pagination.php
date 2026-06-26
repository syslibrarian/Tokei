<?php

declare(strict_types=1);

namespace Tokei\Tool\Pagination;

use Twig\Environment;

use function Tempest\Container\get;
use function Tempest\Support\Str\contains;
use function Tempest\Support\Str\ensure_ends_with;
use function Tempest\Support\Str\replace;

final class Pagination
{
    private(set) int $pages = 0;
    private(set) int $offset = 0;
    private(set) int $limit = 0;

    public function __construct(
        protected(set) int $pageNo,
        protected(set) int $maxItems,
        protected(set) string $uri,
        protected(set) int $perPage = 25,
    ) {
        $this->pages = (int) ceil($this->maxItems / $this->perPage);
        $this->offset = ($this->pageNo - 1) * $this->perPage;
        $this->limit = $perPage;
    }

    public function __toString(): string
    {
        return get(Environment::class)->render('_pagination.tpl', ['pagination' => $this]);
    }

    public function getUri(string $no): string
    {
        if (contains($this->uri, '{no}')) {
            return replace($this->uri, '{no}', $no);
        }

        return ensure_ends_with($this->uri, '/') . $no;
    }
}
