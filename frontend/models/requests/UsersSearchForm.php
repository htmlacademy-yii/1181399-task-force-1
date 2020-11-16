<?php

namespace frontend\models\requests;

use frontend\models\User;
use Yii;
use yii\base\Model;
use yii\data\Pagination;

class UsersSearchForm extends Model
{
    public $categories;
    public $hasFeedback;
    public $online;
    public $free;
    public $searchName;
    public $bookmarked;
    public $sort;

    public function rules()
    {
        return [
            [['categories', 'hasFeedback', 'online', 'free', 'bookmarked', 'searchName'], 'safe'],
            [['searchName'], 'string', 'max' => 100],
            [['hasFeedback', 'online', 'free', 'bookmarked'], 'boolean'],
            ['sort', 'in', 'range' => ['tasks', 'popularity', 'rating']]
        ];
    }

    public function attributeLabels()
    {
        return [
            'categories' => 'Категории',
            'hasFeedback' => 'Без откликов',
            'online' => 'Удаленная работа',
            'searchName' => 'Поиск по имени',
            'free' => 'Свободен',
            'bookmarked' => 'В закладках'
        ];
    }

    private function getUsersQuery()
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
            ->andWhere('private_profile=0')
            ->leftJoin('tasks', 'tasks.executor_id = users.id')
            ->leftJoin('feedback', 'feedback.user_id = users.id')
            ->with('categories')
            ->groupBy(['users.id', 'users.created_at']);


        if (($categories = $this->categories) && is_array($categories)) {
            $users->leftJoin('category_user as cu', 'cu.user_id = users.id')
                ->andWhere(['in', 'cu.category_id', $categories]);
        }

        if ($this->online) {
            $lastVisit = (new \DateTimeImmutable())->modify('-5m')->format('Y-m-d H:i:s');
            $users->andWhere(['>=', 'last_visit', $lastVisit]);
        }

        if ($this->free) {
            $users->andWhere('not exists ' .
                             '(select 1 from tasks where executor_id = users.id and status in ("wip", "new"))'
            );
        }

        if ($this->bookmarked) {
            // здесь интересно. У нас пока нет авторизации, поэтому будем использовать хардкод id пользователя - 1
            $users->andWhere('exists ' .
                             '(select 1 from bookmarks where bookmark_user_id = users.id and bookmarks.user_id = :id)',
                ['id' => Yii::$app->user->getId()]
            );
        }

        if ($this->hasFeedback) {
            $users->leftJoin('feedback as f', 'f.user_id = users.id')
                ->addGroupBy('users.id')
                ->having('count(f.id) > 0');
        }

        $users->andFilterWhere(['like', 'users.name', $this->searchName]);

        if ($this->sort) {
            $users->orderBy($this->getSort($this->sort));
        }

        return $users;
    }

    public function getUsersFromForm()
    {
        $query = $this->getUsersQuery();
        $countQuery = clone $query;

        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $result = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return [$result, $pages];

    }

    private function getSort($sort)
    {
        switch($sort) {
            case 'tasks':
                return 'tasks_count desc';
            case 'popularity':
                return 'views desc';
            case 'rating':
                return 'rating desc';
            default:
                return 'created_at desc';
        }
    }
}
