<?php

namespace frontend\modules\api\controllers;

use frontend\models\Message;
use frontend\models\requests\MessageCreateRequest;
use Yii;

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
}