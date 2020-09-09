<?php

namespace frontend\controllers;

use frontend\models\Category;
use frontend\models\requests\UsersSearchForm;
use Yii;
use yii\web\Controller;

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
}
