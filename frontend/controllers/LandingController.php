<?php

namespace frontend\controllers;

use common\models\LoginForm;
use frontend\models\Task;
use Yii;
use yii\web\Controller;
use yii\helpers\Url;

class LandingController extends Controller
{
    public function actionLanding()
    {
        if (!Yii::$app->user->isGuest) {
            return Yii::$app->response->redirect('/tasks');
        }
        $tasks = Task::find()->orderBy('created_at')->limit(4)->all();

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return Yii::$app->response->redirect('/tasks');
        } else {
            $model->password = '';

            return $this->renderPartial('landing', compact('tasks', 'model'));
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
