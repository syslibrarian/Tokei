<?php

declare(strict_types=1);

namespace Tokei\Command;

use Tempest\Database\Transactions\TransactionManager;

trait IsHandler
{
    public function __construct(
        private readonly TransactionManager $transaction,
        private readonly Response $response,
    ) {}
}
