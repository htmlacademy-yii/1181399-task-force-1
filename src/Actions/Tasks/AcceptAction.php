<?php

namespace Htmlacademy\Actions\Tasks;

use Htmlacademy\Models\Task;

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
    public function getName(): string
    {
        return 'Принять';
    }

    /**
     * @inheritDoc
     */
    public function getSlug(): string
    {
        return 'accept';
    }

    public function nextStatus(): string
    {
        return Task::STATUS_WIP;
    }
}
