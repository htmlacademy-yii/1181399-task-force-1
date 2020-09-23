<?php

namespace frontend\controllers;

use common\models\LoginForm;
use frontend\models\Task;
use Yii;
use yii\base\Controller;
use yii\helpers\Url;

class LandingController extends Controller
{
    public function actionLanding()
    {
        $tasks = Task::find()->orderBy('created_at')->limit(4)->all();
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->renderPartial('landing', compact('tasks', 'model'));
        }
    }

    public function actionLogin()
    {

    }
}
