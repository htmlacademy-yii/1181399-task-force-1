<?php

namespace Htmlacademy\Actions\Tasks;

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
    public function getName(): string
    {
        return 'Завершить заказ';
    }

    /**
     * @inheritDoc
     */
    public function getSlug(): string
    {
        return 'done';
    }

    public function nextStatus(): string
    {
        return Task::STATUS_DONE;
    }
}
