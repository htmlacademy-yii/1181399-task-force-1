<?php

namespace Htmlacademy;

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
        'cancelled' => 'Отменено',
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
        self::STATUS_NEW => [self::ACTION_ACCEPT, self::ACTION_REMOVE],
        self::STATUS_WIP => [self::ACTION_DONE, self::ACTION_DECLINE],
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
    private $idAuthor;
    private $idExecutor;

    public $status;

    public function __construct(int $idExecutor, int $idAuthor)
    {
        $this->idAuthor = $idAuthor;
        $this->idExecutor = $idExecutor;
    }

    public function getActions(): ?array
    {
        if (!array_key_exists($this->status, self::AVAILABLE_ACTIONS)) {
            // Ученик не знает, что можно кидать исключения.
            // А вообще, здесь есть глубокий вопрос, может быть статус стоит кинуть в конструктор и также хранить в приватном поле
            return null;
        }

        return self::AVAILABLE_ACTIONS[$this->status];
    }

    public function getNextStatus(string $action): ?string
    {
        if (!array_key_exists($this->status, self::AVAILABLE_ACTIONS)) {
            return null;
        }

        if (!in_array($action, self::AVAILABLE_ACTIONS[$this->status], true)) {
            return null;
        }

        return self::ACTION_TO_STATUS[$action] ?? null; // nullsafe
    }
}
