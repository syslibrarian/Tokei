<?php

declare(strict_types=1);

namespace Tokei\Model\Journal;

use Tempest\Database\IsDatabaseModel;
use Tempest\Database\Table;
use Tempest\Database\Virtual;

#[Table('journal')]
final class Entry
{
    use IsDatabaseModel;

    public int $user_id;
    public string $user_name;

    public string $model_class;
    public int $model_id;
    public string $changed_fields_raw;
    public int $timestamp;
    public string $action;

    // virtual fields
    #[Virtual]
    public GenericModel $model {
        get {
            if ($this->tmpModel === null) {
                $this->tmpModel = new GenericModel($this->model_id, $this->model_class, $this->changed_fields_raw);
            }

            return $this->tmpModel;
        }
    }

    #[Virtual]
    private ?GenericModel $tmpModel = null;
}
