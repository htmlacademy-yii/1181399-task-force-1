<?php

namespace frontend\controllers;

use frontend\models\Category;
use frontend\models\requests\UsersSearchForm;
use frontend\models\User;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class UsersController extends Controller
{
    public function actionIndex()
    {
        $form = new UsersSearchForm();
        $form->load(Yii::$app->request->get());

        $users = $form->getUsersFromForm();
        $categories = Category::find()->all();

        return $this->render('browse', ['users' => $users, 'request' => $form, 'categories' => $categories]);
    }

    public function actionView($id)
    {
        $user = User::findOne($id);
        if (!$user) {
            throw new NotFoundHttpException("Пользователь с id {$id} не найден");
        }

        return $this->render('show', ['user' => $user]);
    }
}
