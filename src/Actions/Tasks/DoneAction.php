<?php

namespace Htmlacademy\Actions\Tasks;

use Htmlacademy\Enums\Actions;
use Htmlacademy\Models\TaskStateMachine;

class DoneAction extends AbstractAction
{
    /**
     * @inheritDoc
     */
    public function can(int $authorId, ?int $executorId, int $userId): bool
    {
        return $authorId === $userId;
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
        return TaskStateMachine::STATUS_DONE;
    }
}
