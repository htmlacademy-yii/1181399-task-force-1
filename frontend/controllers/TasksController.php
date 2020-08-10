<?php

namespace frontend\controllers;

use frontend\models\Task;
use yii\web\Controller;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $status = Task::STATUS_NEW;
        $tasks = Task::find()
            ->where(['status' => $status])
            ->joinWith('category')
            ->joinWith('city')
            ->orderBy('created_at DESC')
            ->all();
        return $this->render('browse', ['tasks' => $tasks]);
    }
}
