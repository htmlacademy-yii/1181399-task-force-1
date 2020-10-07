<?php

namespace frontend\models\requests;

use frontend\models\Message;
use frontend\models\Task;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

class MessageCreateRequest extends Message
{
    public $message;

    public static function tableName()
    {
        return 'messages';
    }

    public function beforeValidate()
    {
        /** @var Task $task */
        $task = Task::findOne(['id' => $this->task_id]);
        $userId = Yii::$app->user->getId() ?? 21;

        $this->recipient_id = $task->author_id === $userId ? $task->author_id : $task->executor_id;
        $this->author_id = $userId;
        $this->content = $this->message;

        return true;
    }
}
