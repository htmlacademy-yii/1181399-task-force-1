<?php

namespace frontend\modules\api\controllers;

use frontend\models\Message;
use frontend\models\requests\MessageCreateRequest;
use frontend\models\Task;
use Yii;
use yii\rest\ActiveController;
use yii\rest\Controller;

class MessagesController extends ActiveController
{
    public $modelClass = Task::class;

    public function actionIndex($id)
    {
        // Здесь отсутствует параметр $id и роут localhost/api/messages/{id} не открывается.
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
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->createScenario,
            ]
        ];
    }
}
