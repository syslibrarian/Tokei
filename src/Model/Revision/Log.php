<?php

declare(strict_types=1);

namespace Tokei\Model\Revision;

use Tempest\Database\IsDatabaseModel;

final class Log
{
    use IsDatabaseModel;

    public int $type;
    public int $model_id;
    public string $model_class;
    public int $user_id;
    public string $user_name;
    public string $content;
    public string $message;
    public int $timestamp;
}
