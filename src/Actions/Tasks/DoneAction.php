<?php

namespace Htmlacademy\Actions\Tasks;

use Htmlacademy\Enums\Actions;
use Htmlacademy\Models\Task;

class DoneAction extends AbstractAction
{
    /**
     * @inheritDoc
     */
    public function can(int $authorId, ?int $executorId, int $userId): bool
    {
        return $executorId === $userId;
    }

    /**
     * @inheritDoc
     */
    public static function getName(): string
    {
        return 'Завершить заказ';
    }

    /**
     * @inheritDoc
     */
    public static function getSlug(): string
    {
        return Actions::DONE;
    }

    public static function nextStatus(): string
    {
        return Task::STATUS_DONE;
    }
}
