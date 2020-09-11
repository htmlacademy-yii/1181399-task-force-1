<?php

namespace frontend\controllers;

use frontend\models\Category;
use frontend\models\requests\TasksSearchForm;
use frontend\models\Task;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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

    public function actionView($id)
    {
        $task = Task::findOne($id);
        if (!$task) {
            throw new NotFoundHttpException("Задание с ID {$id} не существует!");
        }

        return $this->render('show', ['task' => $task]);
    }
}
