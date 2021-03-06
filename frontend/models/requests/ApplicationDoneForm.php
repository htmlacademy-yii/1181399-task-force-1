<?php

namespace frontend\models\requests;

use frontend\models\Application;
use frontend\models\Feed;
use frontend\models\Feedback;
use frontend\models\Task;
use frontend\services\notifications\NotificationService;
use Htmlacademy\Models\TaskStateMachine;
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
            [['rating', 'comment', 'taskId', 'done'], 'safe'],
            [['rating', 'comment', 'taskId', 'done'], 'required'],
            [['comment'], 'string', 'min' => 1],
            [['rating'], 'number', 'min' => 1, 'max' => 5],
            [['taskId'], 'exist', 'targetClass' => Task::class, 'targetAttribute' => 'id'],
            [['done'], 'in', 'range' => ['done', 'difficulties']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'budget' => 'Бюджет',
            'comment'=> 'Комментарий'
        ];
    }

    /**
     * Завершение задания
     *
     * @return bool
     */
    public function finishTask()
    {
        if (!$this->validate()) {
            return false;
        }
        switch($this->done){
            case 'difficulties':
                return $this->setDifficulties();
            case 'done':
                return $this->setDone();
        }
    }

    /**
     * Завершение задания с установкой флага "выполнено плохо".
     *
     * @return false
     */
    public function setDifficulties()
    {
        if (!$task = $this->getTask()) {
            return false;
        }

        $task->status = TaskStateMachine::STATUS_FAILED;
        $task->save();

        $this->addFeedback($task, TaskStateMachine::STATUS_FAILED);
    }

    /**
     * Успешное завершение задания.
     *
     * @return bool
     */
    public function setDone()
    {
        if (!$task = $this->getTask()) {
            return false;
        }

        $task->status = TaskStateMachine::STATUS_DONE;
        $task->save();

        $this->addFeedback($task);
        return true;
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

    /**
     * Создание модели отзыва.
     *
     * @param Task $task
     * @param string $status
     * @throws \Throwable
     */
    private function addFeedback(Task $task, string $status = 'success')
    {
        $feedback = new Feedback();
        $feedback->author_id = $task->author_id;
        $feedback->user_id = $task->executor_id;
        $feedback->task_id = $task->id;
        $feedback->comment = $this->comment;
        $feedback->rating = $this->rating;
        $feedback->status = $status;

        $feedback->save();



        $notification = new NotificationService();
        $notification->notify(
            Yii::$app->user->getIdentity(),
            Feed::FEEDBACK,
            $feedback->task_id
        );
    }
}
