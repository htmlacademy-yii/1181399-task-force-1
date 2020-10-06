<?php

namespace frontend\models\requests;

use frontend\models\Message;
use Yii;
use yii\base\Model;

class MessageCreateRequest extends Model
{
    public $taskId;
    public $message;

    public function rules()
    {
        return [
            ['message', 'required'],
            ['message', 'safe'],
            ['message', 'string', 'min' => 1],
        ];
    }

    public function save()
    {
        $message = new Message();
        $message->author_id = Yii::$app->user->getId();
        $message->task_id = $this->taskId;
        $message->content = $this->message;
        $message->save();
        return $message;
    }
}
