<?php

declare(strict_types=1);

namespace Tokei\Tool\Journal;

use Tempest\Database\PrimaryKey;
use Tempest\DateTime\Timestamp;
use Tokei\Model\Journal\Entry;
use Tokei\Tool\Journal\Actions;

final class ModelLogger
{
    /** @var object&\Tempest\Database\IsDatabaseModel */
    private(set) readonly object $model;
    private(set) readonly string $className;

    /**
     * @param object&\Tempest\Database\IsDatabaseModel $model
     */
    private function __construct(object $model)
    {
        $this->model = clone $model;
        $this->className = get_class($model);
    }

    public function created(bool $system = false): void
    {
        $user = $this->getUserData();
        Entry::create(
            user_id: $user['id'],
            user_name: $user['name'],
            model_id: $this->model->id->value,
            timestamp: Timestamp::now()->getSeconds(),
            action: ($system) ? Actions::SYS_CREATE : Actions::CREATE,
        );
    }

    /**
     * @param object&\Tempest\Database\IsDatabaseModel $model
     * @param bool $system
     * @return void
     */
    public function update(object $model, bool $system = false): void
    {
        if (($model instanceof $this->className) === false) {
            throw new \InvalidArgumentException('Model class must be an instance of ' . $this->className);
        }

        $user = $this->getUserData();
        Entry::create(
            user_id: $user['id'],
            user_name: $user['name'],
            model_id: $this->model->id->value,
            timestamp: Timestamp::now()->getSeconds(),
            action: ($system) ? Actions::SYS_UPDATE : Actions::UPDATE,
            changed_fields_raw: $this->getChangedData($model)
        );
    }

    protected function getChangedData(object $model): string
    {
        return ''; // todo check object attributes for changes and return only changed data.
    }

    protected function getUserData(bool $system): array
    {
        return ($system) ? ['id' => 0, 'name' => ''] : ['id' => 0, 'name' => '']; // Todo: get session user data.
    }


    /**
     * @param object&\Tempest\Database\IsDatabaseModel $model
     * @return self|null
     */
    public static function forModel(object $model): ?self
    {
        return (isset($model->id) || $model->id instanceof PrimaryKey) ? new self($model) : null;
    }
}
