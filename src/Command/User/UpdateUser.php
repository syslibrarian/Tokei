<?php

declare(strict_types=1);

namespace Tokei\Command\User;

use Tokei\Command\Command;
use Tokei\Model\User\User;

final class UpdateUser implements Command
{
    public function __construct(
        public User $model,
        public string $username,
        public string $name,
        public string $surname,
        public string $email,
        #[\SensitiveParameter]
        public int $change_password,
        #[\SensitiveParameter]
        public string $password,
        #[\SensitiveParameter]
        public string $password_repeat,
        public string $seal,
        public int $role_id,
    ) {}
}
