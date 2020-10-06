<?php

namespace frontend\models\requests;

use frontend\models\Message;
use frontend\models\Task;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

class MessageCreateRequest extends ActiveRecord
{
    public $task_id;
    public $message;

    public static function tableName()
    {
        return 'messages';
    }

    public function rules()
    {
        return [
            [['message', 'task_id'], 'required'],
            [['message', 'task_id'], 'safe'],
            ['message', 'string', 'min' => 1],
            ['task_id', 'exist', 'targetClass' => Task::class, 'targetAttribute' => 'id'],
        ];
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var Task $task */
        $task = Task::findOne(['id' => $this->task_id]);
        $userId = Yii::$app->user->getId() ?? 21;

        $message = new Message();
        $message->recipient_id = $task->author_id === $userId ? $task->author_id : $task->executor_id;
        $message->author_id = $userId;
        $message->task_id = $this->task_id;
        $message->content = $this->message;

        return $message->save();
    }
}
