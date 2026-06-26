<?php
declare(strict_types=1);

namespace Tokei\Command\User;

use Tokei\Command\Command;
use Tokei\Model\User\User;

final class DeleteUser implements Command
{
    public function __construct(
        public User $model,
    ) {}
}
