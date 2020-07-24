<?php

namespace Htmlacademy\Actions\Tasks;

use Htmlacademy\Models\Task;

class RemoveAction extends AbstractAction
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
        return 'remove';
    }

    public function nextStatus(): string
    {
        return Task::STATUS_CANCELED;
    }
}
