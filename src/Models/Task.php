<?php

namespace Htmlacademy\Models;

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

    const ACTION_ACCEPT = 'accept';
    const ACTION_REMOVE = 'remove';
    const ACTION_DONE = 'done';
    const ACTION_DECLINE = 'decline';

    const ACTION_NAMES = [
        self::ACTION_ACCEPT => 'Откликнуться',
        self::ACTION_REMOVE => 'Отменить',
        self::ACTION_DONE => 'Отметить выполненным',
        self::ACTION_DECLINE => 'Отказаться'
    ];

    const AVAILABLE_ACTIONS = [
        self::STATUS_NEW => [
            'any' => [self::ACTION_ACCEPT],
            'author' => [self::ACTION_REMOVE],
        ],
        self::STATUS_WIP => [
            'executor' => [self::ACTION_DONE],
            'author' => [self::ACTION_DECLINE],
        ],
        self::STATUS_CANCELED => [],
        self::STATUS_FAILED => [],
        self::STATUS_DONE => [],
    ];

    const ACTION_TO_STATUS = [
        self::ACTION_ACCEPT => self::STATUS_WIP,
        self::ACTION_REMOVE => self::STATUS_CANCELED,
        self::ACTION_DONE => self::STATUS_DONE,
        self::ACTION_DECLINE => self::STATUS_FAILED,
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

        $type = $this->getUserTypeById($userId);

        if (!array_key_exists($this->status, self::AVAILABLE_ACTIONS)) {
            return null;
        }

        return self::AVAILABLE_ACTIONS[$this->status][$type] ?? [];
    }

    public function getNextStatus(string $action): ?string
    {
        if (!$this->status) {
            return null;
        }

        if (!array_key_exists($this->status, self::AVAILABLE_ACTIONS)) {
            return null;
        }

        $actions = array_merge(
            self::AVAILABLE_ACTIONS[$this->status]['executor'] ?? [],
            self::AVAILABLE_ACTIONS[$this->status]['author'] ?? [],
            self::AVAILABLE_ACTIONS[$this->status]['any'] ?? []
        );

        if (!in_array($action, $actions, true)) {
            return null;
        }

        return self::ACTION_TO_STATUS[$action] ?? null; // nullsafe
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
