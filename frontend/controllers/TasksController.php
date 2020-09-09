<?php

namespace frontend\controllers;

use frontend\models\Category;
use frontend\models\requests\TasksSearchForm;
use Yii;
use yii\web\Controller;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $form = new TasksSearchForm();
        $form->load(Yii::$app->request->get());

        $tasks = $form->getTasks();
        $categories = Category::find()->all();

        return $this->render('browse', ['tasks' => $tasks, 'request' => $form, 'categories' => $categories]);
    }
}
