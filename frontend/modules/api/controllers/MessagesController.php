<?php

namespace frontend\modules\api\controllers;

use frontend\models\Message;
use frontend\models\requests\MessageCreateRequest;
use frontend\models\Task;
use Yii;
use yii\filters\AccessControl;
use yii\rest\ActiveController;
use yii\rest\Controller;
use yii\web\Response;

class MessagesController extends ActiveController
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

    public function behaviors()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            $taskId = Yii::$app->request->get('task_id') ?? Yii::$app->request->post('task_id');
                            if (!$taskId) {
                                return false;
                            }

                            $task = Task::findOne(['id' => $taskId]);
                            if (!$task) {
                                return false;
                            }

                            $userId = Yii::$app->user->getId();
                            return $userId === $task->author_id || $userId === $task->executor_id;
                        }
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?']
                    ]
                ]
            ]
        ];
    }
}
