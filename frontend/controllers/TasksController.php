<?php

namespace frontend\controllers;

use frontend\models\Category;
use frontend\models\Task;
use Yii;
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
            ->orderBy('created_at DESC');

        if (Yii::$app->request->get('remote')) {
            $tasks->where('address is null');
        }

        if (Yii::$app->request->get('no_applications')) {
            $tasks->leftJoin('applications', 'applications.task_id = id')
                ->having('count(applications.id) > 0')
                ->groupBy('tasks.id');
        }

        $name = Yii::$app->request->get('name');
        if ($name && $name !== '') {
            $tasks->where(['like', 'title', "%$name%"]);
        }

        if (Yii::$app->request->get('period')) {

        }

        $tasks = $tasks->all();

        $categories = Category::find()->all();

        return $this->render('browse', ['tasks' => $tasks, 'categories' => $categories]);
    }
}
