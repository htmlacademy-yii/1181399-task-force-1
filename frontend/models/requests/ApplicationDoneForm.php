<?php

namespace frontend\models\requests;

use frontend\models\Application;
use frontend\models\Feedback;
use frontend\models\Task;
use Yii;
use yii\base\Model;

class ApplicationDoneForm extends Model
{
    public $comment;
    public $rating;
    public $done;
    public $taskId;

    public function rules()
    {
        return [
            [['rating', 'comment', 'task_id', 'done'], 'safe'],
            [['rating', 'comment', 'task_id', 'done'], 'required'],
            [['comment'], 'string', 'min' => 1],
            [['rating'], 'number', 'min' => 1, 'max' => 5],
            [['task_id'], 'exist', 'targetClass' => Task::class, 'targetAttribute' => 'id'],
            [['done'], 'in', 'range' => ['difficulties', 'done']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'budget' => 'Бюджет',
            'comment'=> 'Комментарий'
        ];
    }

    public function finishTask()
    {
        $this->validate();
        switch($this->done){
            case 'difficulties':
                return $this->setDifficulties();
            case 'done':
                return $this->setDone();
        }
    }

    /**
     * @return Task|false
     */
    private function getTask()
    {
        $task = Task::findOne(['id' => $this->taskId]);
        if (!$task || !$task->author_id === Yii::$app->user->getId()) {
            return false;
        }
        return $task;
    }

    public function setDifficulties()
    {
        if (!$task = $this->getTask()) {
            return false;
        }

        $task->status = 'failed';
        $task->save();

        $this->addFeedback($task);
    }

    public function setDone()
    {
        if (!$task = $this->getTask()) {
            return false;
        }

        $task->status = 'done';
        $task->save();

        $this->addFeedback($task);
        return true;
    }

    private function addFeedback(Task $task)
    {
        $feedback = new Feedback();
        $feedback->author_id = $task->author_id;
        $feedback->user_id = $task->executor_id;
        $feedback->comment = $this->comment;
        $feedback->rating = $this->rating;

        $feedback->save();
    }
}
