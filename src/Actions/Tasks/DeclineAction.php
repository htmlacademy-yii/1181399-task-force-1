<?php

namespace Htmlacademy\Actions\Tasks;

use Htmlacademy\Enums\Actions;
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
        return Task::STATUS_FAILED;
    }
}
