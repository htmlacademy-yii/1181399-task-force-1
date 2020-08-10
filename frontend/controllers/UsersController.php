<?php

namespace frontend\controllers;

use DateTime;
use frontend\models\Category;
use frontend\models\requests\TasksSearchForm;
use frontend\models\requests\UsersSearchForm;
use frontend\models\User;
use frontend\models\UsersQuery;
use Yii;
use yii\db\Query;
use yii\web\Controller;

class UsersController extends Controller
{
    public function actionIndex()
    {
        $request = new UsersSearchForm();
        $request->load(Yii::$app->request->get());
        if (!$request->validate()) {
            return $this->redirect('/tasks');
        }



        $users = $this->getUsers($request);
        $categories = Category::find()->all();

        return $this->render('browse', ['users' => $users, 'request' => $request, 'categories' => $categories]);
    }

    private function getUsers(UsersSearchForm $request)
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


        if (($categories = $request->categories) && is_array($categories)) {
            $users->leftJoin('category_user as cu', 'cu.user_id = users.id')
                ->andWhere(['in', 'cu.category_id', $categories]);
        }

        if ($request->online) {
            $lastVisit = (new \DateTimeImmutable())->modify('-5m')->format('Y-m-d H:i:s');
            $users->andWhere(['>=', 'last_visit', $lastVisit]);
        }

        if ($request->free) {
            $users->andWhere('not exists ' .
                             '(select 1 from tasks where executor_id = users.id and status in ("wip", "new"))'
            );
        }

        if ($request->bookmarked) {
            // здесь интересно. У нас пока нет авторизации, поэтому будем использовать хардкод id пользователя - 1
            $users->andWhere('exists ' .
                             '(select 1 from bookmarks where bookmark_user_id = users.id and bookmarks.user_id = 1)'
            );
        }

        if ($request->hasFeedback) {
            $users->leftJoin('feedback as f', 'f.user_id = users.id')
                ->groupBy('users.id')
                ->having('count(f.id) > 0');
        }

        if (isset($request->searchName) && $request->searchName) {
            $users->andWhere(['like', 'users.name', $request->searchName]);
        }

        return $users->all();
    }
}
