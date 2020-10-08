<?php

namespace frontend\modules\api\controllers;

use frontend\models\Message;
use frontend\models\requests\MessageCreateRequest;
use frontend\models\Task;
use Yii;
use yii\web\ForbiddenHttpException;

class MessagesController extends SecuredRestController
{
    public $modelClass = Message::class;

    public function actionIndex()
    {
        if (!$id = Yii::$app->request->get('task_id')) {
            return false;
        }
        return Message::find()->where(['task_id' => $id])->all();
    }

    public function actions()
    {
        return [
            'index' => [
                'class' => 'yii\rest\IndexAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'prepareDataProvider' => [$this, 'actionIndex'],
            ],
            'create' => [
                'class' => 'yii\rest\CreateAction',
                'modelClass' => MessageCreateRequest::class,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->createScenario,
            ]
        ];
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if (!Yii::$app->request->get('task_id') && !Yii::$app->request->post('task_id')) {

            throw new ForbiddenHttpException();
        }
        $taskId = Yii::$app->request->get('task_id') ?? Yii::$app->request->post('task_id');
        $task = Task::findOne(['id' => $taskId]);

        if (!$task) {
            throw new ForbiddenHttpException();
        }

        $userId = Yii::$app->user->getId();
        if ($task->author_id !== $userId || $task->executor_id !== $userId) {
            throw new ForbiddenHttpException();
        }
    }
}
