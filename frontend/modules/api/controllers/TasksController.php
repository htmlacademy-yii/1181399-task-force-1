<?php

namespace frontend\modules\api\controllers;

use frontend\models\Task;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;

class TasksController extends ActiveController
{
    public $modelClass = Task::class;

    /**
     * Вытаскиваем список заданий в соответствии с тз.
     *
     * @return array|Task[]
     */
    public function actionIndex()
    {
        return Task::find()->where(['=', 'author_id', \Yii::$app->user->getId()])
            ->orWhere(['=', 'executor_id', \Yii::$app->user->getId()])->all();
    }

    public function actions()
    {
        return [
            'index' => [
                'class' => 'yii\rest\IndexAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'prepareDataProvider' => [$this, 'actionIndex'],
            ]
        ];
    }
}
