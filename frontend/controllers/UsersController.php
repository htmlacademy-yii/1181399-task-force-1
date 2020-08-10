<?php

namespace frontend\controllers;

use frontend\models\User;
use frontend\models\UsersQuery;
use yii\db\Query;
use yii\web\Controller;

class UsersController extends Controller
{
    public function actionIndex()
    {
        $users = User::find()
            ->select(
                [
                    'users.*',
                    'count(tasks.id) as tasks_count',
                    'count(feedback.id) as feedback_count',
                    'round(avg(feedback.rating), 2) as rating',
                ]
            )
            ->where('exists(select * from category_user where users.id = user_id)')
            ->leftJoin('tasks', 'tasks.executor_id = users.id')
            ->leftJoin('feedback', 'feedback.user_id = users.id')
            ->with('categories')
            ->orderBy('created_at desc')
            ->groupBy('users.id')
            ->all();
        return $this->render('browse', ['users' => $users]);
    }
}
