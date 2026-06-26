<?php

declare(strict_types=1);

namespace Tokei\Model\Navigation;

use Tempest\Database\HasMany;
use Tempest\Database\IsDatabaseModel;
use Tempest\Database\Table;
use Tempest\Validation\Rules\IsNotEmptyString;
use Tempest\Validation\Rules\MatchesRegEx;
use Tokei\Tokei;

#[Table('navigation')]
final class Navigation
{
    use IsDatabaseModel;

    #[IsNotEmptyString, MatchesRegEx('/^[a-zA-Z0-9]+([\.\-_a-zA-Z0-9]+[a-zA-Z0-9])*$/')]
    public string $name;

    public ?int $parent_id;

    /** @var \Tokei\Model\Navigation\Item[] */
    #[HasMany(ownerJoin: 'navigation_item.navigation_id', relationJoin: 'navigation.id')]
    public array $items = [];

    public bool $is_system;

    public bool $is_admin;

    public string $view_name;
}
