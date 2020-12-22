<?php

namespace frontend\controllers;

use common\models\LoginForm;
use frontend\models\Task;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class LandingController extends Controller
{
    /**
     * Главная страница лендинга.
     *
     * @return string|\yii\web\Response
     */
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

    /**
     * Действие выхода из аккаунта
     *
     * @return \yii\web\Response
     */
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
                'denyCallback' => function ($rule, $action) {
                    return $this->redirect('/tasks');
                },
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
