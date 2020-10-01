<?php

namespace Htmlacademy\Actions\Tasks;

use Htmlacademy\Enums\Actions;
use Htmlacademy\Models\TaskStateMachine;

class DeclineAction extends AbstractAction
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
        return 'Отменить заказ';
    }

    /**
     * @inheritDoc
     */
    public static function getSlug(): string
    {
        return Actions::DECLINE;
    }

    public static function nextStatus(): string
    {
        return TaskStateMachine::STATUS_FAILED;
    }
}
