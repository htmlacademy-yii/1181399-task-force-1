<?php

namespace Htmlacademy\Actions\Tasks;

use Htmlacademy\Enums\Actions;
use Htmlacademy\Models\TaskStateMachine;

class AcceptAction extends AbstractAction
{
    /**
     * @inheritDoc
     */
    public function can(int $authorId, ?int $executorId, int $userId): bool
    {
        return $executorId && $executorId === $userId;
    }

    /**
     * @inheritDoc
     */
    public static function getName(): string
    {
        return 'Принять';
    }

    /**
     * @inheritDoc
     */
    public static function getSlug(): string
    {
        return Actions::ACCEPT;
    }

    public static function nextStatus(): string
    {
        return TaskStateMachine::STATUS_WIP;
    }
}
