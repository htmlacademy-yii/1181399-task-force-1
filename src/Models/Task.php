<?php

namespace Htmlacademy\Models;

use Htmlacademy\Actions\Tasks\AbstractAction;
use Htmlacademy\Actions\Tasks\AcceptAction;
use Htmlacademy\Actions\Tasks\DeclineAction;
use Htmlacademy\Actions\Tasks\DoneAction;
use Htmlacademy\Actions\Tasks\RemoveAction;
use Htmlacademy\Enums\Actions;

class Task
{
    // Вся эта история со статусами до боли напоминает паттерн State machine.
    // Его реализовывать не стал, так как попытался вжиться в роль студента, только закончившего первый курс.
    const STATUS_NEW = 'new';
    const STATUS_CANCELED = 'canceled';
    const STATUS_WIP = 'wip';
    const STATUS_DONE = 'done';
    const STATUS_FAILED = 'failed';

    // В идеале, здесь ученик должен понимать, что он может вызвать константу через ключ. слово self.
    // self::STATUS_NEW => 'Новое'.
    const STATUS_NAMES = [
        'new' => 'Новое',
        'canceled' => 'Отменено',
        'wip' => 'В работе',
        'done' => 'Завершено',
        'failed' => 'Провалено',
    ];

    const AVAILABLE_ACTIONS = [
        self::STATUS_NEW => [AcceptAction::class, RemoveAction::class],
        self::STATUS_WIP => [DoneAction::class, DeclineAction::class],
    ];

    // В материалах к задаче было указано, что поля инициализируются значениями. Но не сказано, что их можно не указывать.
    private $idAuthor = null;
    private $idExecutor = null;

    private $status;

    public function __construct(int $idExecutor, int $idAuthor)
    {
        $this->idAuthor = $idAuthor;
        $this->idExecutor = $idExecutor;
    }

    public function setStatus(string $status): bool
    {
        if (!array_key_exists($status, self::STATUS_NAMES)) {
            return false;
        }

        $this->status = $status;
        return true;
    }

    public function getActions(int $userId): ?array
    {
        if (!$this->status) {
            return null;
        }

        $availableActions = [];
        if (array_key_exists($this->status, self::AVAILABLE_ACTIONS)) {
            foreach (self::AVAILABLE_ACTIONS[$this->status] as $action) {
                /** @var AbstractAction $action */
                $action = new $action();
                if ($action->can($this->idAuthor, $this->idExecutor, $userId)) {
                    $availableActions[] = $action->getSlug();
                }
            }
        }

        return $availableActions;
    }

    public function getNextStatus(string $action): ?string
    {
        if (!$this->status || !isset(self::AVAILABLE_ACTIONS[$this->status])) {
            return null;
        }

        foreach (self::AVAILABLE_ACTIONS[$this->status] as $act) {
            if ($act::getSlug() === $action) {
                return $act::nextStatus();
            }
        }

        return null;
    }

    private function getUserTypeById(int $userId)
    {
        switch($userId) {
            case $this->idExecutor:
                $type = 'executor';
                break;
            case $this->idAuthor:
                $type = 'author';
                break;
            default:
                $type = 'any';
        }

        return $type;
    }
}
