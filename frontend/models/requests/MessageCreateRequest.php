<?php

namespace frontend\models\requests;

use frontend\models\Message;
use frontend\models\Task;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

class MessageCreateRequest extends Message
{
    public $task_id;
    public $message;

    public function beforeValidate()
    {
        /** @var Task $task */
        $task = Task::findOne(['id' => $this->task_id]);
        $userId = Yii::$app->user->getId();

        if (!$userId) {
            throw new \InvalidArgumentException("User ID is not defined");
        }

        $this->message = Yii::$app->request->post('message');
        $this->recipient_id = $task->author_id === $userId ? $task->author_id : $task->executor_id;
        $this->author_id = $userId;
        $this->content = Yii::$app->request->post('message');
        $this->task_id = Yii::$app->request->post('task_id');

        var_dump($this);
        die();
    }
}
