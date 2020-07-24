<?php

namespace Htmlacademy\Actions\Tasks;

use Htmlacademy\Models\Task;

class DeclineAction extends AbstractAction
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
    public function getName(): string
    {
        return 'Отменить заказ';
    }

    /**
     * @inheritDoc
     */
    public function getSlug(): string
    {
        return 'decline';
    }

    public function nextStatus(): string
    {
        return Task::STATUS_FAILED;
    }
}
