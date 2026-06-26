<?php

declare(strict_types=1);

namespace Tokei\Command\User;

use Tokei\Command\Command;

final class CreateUser implements Command
{
    public function __construct(
        public string $username,
        public string $name,
        public string $surname,
        #[\SensitiveParameter]
        public string $email,
        #[\SensitiveParameter]
        public string $password,
        #[\SensitiveParameter]
        public string $password_repeat,
        public string $seal,
        public int $role_id,
    ) {}
}
