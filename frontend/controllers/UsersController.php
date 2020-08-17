<?php

namespace frontend\controllers;

use DateTime;
use frontend\models\User;
use frontend\models\UsersQuery;
use Yii;
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
            ->groupBy('users.id');


        if (($categories = Yii::$app->request->get('categories')) && is_array($categories)) {
            $users->leftJoin('category_user as cu', 'cu.user_id = users.id')
                ->where(['in', 'cu.category_id', $categories]);
        }

        if (Yii::$app->request->get('online')) {
            $lastVisit = (new \DateTimeImmutable())->modify('-5m')->format('Y-m-d H:i:s');
            $users->where(['>=', 'last_visit', $lastVisit]);
        }

        if (Yii::$app->request->get('free')) {
            $users->where('not exists ' .
                '(select 1 from tasks where executor_id = users.id and status in ("wip", "new"))'
            );
        }

        if (Yii::$app->request->get('bookmarked')) {
            // здесь интересно. У нас пока нет авторизации, поэтому будем использовать хардкод id пользователя - 1
            $users->where('exists ' .
              '(select 1 from bookmarks where bookmark_user_id = users.id and bookmarks.user_id = 1)'
            );
        }

        if (Yii::$app->request->get('hasFeedback')) {
            $users->leftJoin('feedbacks', 'user_id = users.id')
                ->groupBy('users.id')
                ->having('count(feedback.id) > 0');
        }


        return $this->render('browse', ['users' => $users->all()]);
    }
}
