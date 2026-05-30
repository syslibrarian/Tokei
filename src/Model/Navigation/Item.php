<?php

declare(strict_types=1);

namespace Tokei\Model\Navigation;

use Tempest\Database\IsDatabaseModel;
use Tempest\Database\Table;

#[Table('navigation_item')]
final class Item
{
    use IsDatabaseModel;

    public int $navigation_id;

    public ?int $page_id;

    public string $name;

    public string $target;

    public int $position;

    public bool $is_active;
}
