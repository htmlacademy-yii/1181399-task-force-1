<?php

namespace frontend\controllers;

use common\models\LoginForm;
use frontend\models\Task;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\helpers\Url;

class LandingController extends Controller
{
    public function actionLanding()
    {
        $tasks = Task::find()->orderBy('created_at')->limit(4)->all();

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect('/tasks');
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

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['landing'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }
}
