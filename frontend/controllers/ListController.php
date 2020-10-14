<?php

namespace frontend\controllers;

use frontend\models\Task;
use Yii;

class ListController extends SecuredController
{
    public function actionIndex()
    {
        $tasks = Task::find()->filterWhere(
            [
                'or',
                ['author_id' => Yii::$app->user->id],
                ['executor_id' => Yii::$app->user->id]
            ]
        )->andFilterWhere(['status' => Yii::$app->request->get('status') ?? 'new'])
        ->all();


        return $this->render('index', ['tasks' => $tasks]);
    }
}
