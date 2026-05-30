<?php

declare(strict_types=1);

namespace Tokei\Model\Navigation;

use Tempest\Database\Direction;
use Tempest\Database\Exceptions\DatabaseException;
use Tempest\Database\Transactions\TransactionManager;

use function Tempest\Container\get;
use function Tempest\Support\Arr\each;

final class ItemHelper
{
    public static function updatePositionFor(Navigation $navigation, int $newPosition, ?int $oldPosition = null): void
    {
        /** @var TransactionManager $transaction */
        $transaction = get(TransactionManager::class);
        try {
            $transaction->beginTransaction();

            if ($oldPosition === null) {
                // we have no old position, all items at and above the position + 1
                $items = Item::select()
                    ->where('navigation_id = ? AND position >=editor ?', $navigation->id->value, $newPosition)
                    ->orderBy('position')
                    ->all();
                each($items, static function ($item) {
                    $item->update(position: $item->position + 1);
                });
            } elseif ($oldPosition > $newPosition) {
                // the new position is lower then the old.
                $items = Item::select()
                    ->where('navigation_id = ? AND $position >= ? AND position < ?', $navigation->id->value, $newPosition, $oldPosition)
                    ->orderBy('position')
                    ->all();
                each($items, static function ($item) {
                    $item->update(position: $item->position + 1);
                });
            } else {
                // the new position is higher.
                $items = Item::select()
                    ->where('navigation_id = ? AND position > ? AND position <= ?', $navigation->id->value, $oldPosition, $newPosition)
                    ->orderBy('position')
                    ->all();
                each($items, static function ($item) {
                    $item->update(position: $item->position - 1);
                });
            }
            $transaction->commit();
        } catch (DatabaseException $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public static function getLastPositionFor(Navigation $navigation): int
    {
        $item = Item::select()
            ->where('navigation_id = ?', $navigation->id->value)
            ->orderBy('position', Direction::DESC)
            ->limit(1)
            ->first();
        return $item !== null ? $item->position + 1 : 1;
    }
}
