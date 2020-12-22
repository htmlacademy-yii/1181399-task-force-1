<?php

namespace frontend\models\requests;

use frontend\models\Feed;
use frontend\models\Message;
use frontend\models\Task;
use frontend\services\notifications\NotificationService;
use Yii;

class MessageCreateRequest extends Message
{
    public $message;

    /**
     * Перед созданием сообщения мы добавим необходимые поля и создадим уведомления для всех пользователей.
     *
     * @return bool
     */
    public function beforeValidate()
    {
        /** @var Task $task */
        $task = Task::findOne(['id' => $this->task_id]);
        $userId = Yii::$app->user->getId();

        $this->recipient_id = $task->author_id === $userId ? $task->author_id : $task->executor_id;
        $this->author_id = $userId;
        $this->content = $this->message;

        $this->createNotification($this->task_id);

        return true;
    }

    /**
     * Создает уведомление
     * @param $task_id
     * @throws \Throwable
     */
    private function createNotification($task_id)
    {
        $notification = new NotificationService();
        $notification->notify(
            Yii::$app->user->getIdentity(),
            Feed::END,
            $task_id
        );
    }
}
