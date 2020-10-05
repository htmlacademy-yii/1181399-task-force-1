<?php

namespace frontend\models\requests;

use frontend\models\Application;
use frontend\models\Task;
use Yii;
use yii\base\Model;

class ApplicationCreateForm extends Model
{
    public $comment;
    public $budget;
    public $task_id;

    public function rules()
    {
        return [
            [['budget', 'comment', 'task_id'], 'safe'],
            [['budget', 'comment', 'task_id'], 'required'],
            [['comment'], 'string', 'min' => 1],
            [['budget'], 'number', 'min' => '1'],
            [['task_id'], 'exist', 'targetClass' => Task::class, 'targetAttribute' => 'id'],
            [['task_id'], 'uniqueApplication'],
            [['task_id'], 'userIsExecutor']
        ];
    }

    public function attributeLabels()
    {
        return [
            'budget' => 'Бюджет',
            'comment'=> 'Комментарий'
        ];
    }

    public function createApplication()
    {
        $application = new Application();
        $application->task_id = $this->task_id;
        $application->comment = $this->comment;
        $application->budget = $this->budget;
        $application->user_id = Yii::$app->user->getId();

        return $application->save();
    }

    public function create()
    {
        $task = Task::findOne(['id' => $this->task_id]);
        if ($task->author_id === Yii::$app->user->getId()) {
            return false;
        }

        if ($this->validate()) {
            return $this->createApplication();
        }
        return false;
    }

    public function uniqueApplication()
    {
        if (Yii::$app->user->getIdentity()->applied($this->task_id)) {
            $this->addError('task_id', "Вы уже создавали заявку на эту задачу");
            return false;
        }

        return true;
    }

    public function userIsExecutor()
    {
        if (Yii::$app->user->getIdentity()->isAuthor()) {
            $this->addError('task_id', 'Вы должны являться исполнителем.');
            return false;
        }
        return true;
    }
}
