<?php

namespace frontend\modules\api\controllers;

use frontend\models\Message;
use frontend\models\requests\MessageCreateRequest;
use frontend\models\Task;
use Yii;
use yii\rest\ActiveController;
use yii\rest\Controller;

class MessagesController extends Controller
{
    public function actionView($id)
    {
        return Message::findAll(['task_id' => $id]);
    }

    public function actionCreate($id)
    {
        $model = new MessageCreateRequest();
        $model->load(Yii::$app->request->post());
        if ($model->validate()) {
            return $model->save();
        }
        return false;
    }
}
