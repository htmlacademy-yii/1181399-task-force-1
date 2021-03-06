<?php

namespace frontend\models\requests;

use frontend\models\Task;
use Yii;
use yii\base\Model;
use yii\data\Pagination;

class TasksSearchForm extends Model
{
    public $categories;
    public $withoutApplications;
    public $remote;
    public $period;
    public $searchName;

    public function __construct($config = [])
    {
        parent::__construct($config);

        if (Yii::$app->request->get('categories')) {
            $this->categories = Yii::$app->request->get('categories');
        }
    }

    public function rules()
    {
        return [
            [['categories', 'withoutApplications', 'remote', 'period', 'searchName'], 'safe'],
            [['searchName'], 'string', 'max' => 100],
            [['withoutApplications', 'remote'], 'boolean'],
            [['period'], 'in', 'range' => ['day', 'week', 'month']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'categories' => 'Категории',
            'withoutApplications' => 'Без откликов',
            'remote' => 'Удаленная работа',
            'period' => 'Период',
            'searchName' => 'Поиск по имени',
        ];
    }

    /**
     * Возвращает карту соответствий для периода
     *
     * @return string[]
     */
    public function getPeriods() {
        return [
            '' => 'Период',
            'day' => 'День',
            'week' => 'Неделя',
            'month' => 'Месяц',
        ];
    }

    /**
     * Вытащим список заданий.
     *
     * @return array
     */
    public function getTasks()
    {
        $query = $this->prepareTasksQuery();
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize' => 5]);
        $result = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        return [$result, $pages];

    }

    /**
     * Подготовим запрос для выдачи подсчета количества или результата запроса.
     *
     * @return \frontend\models\TasksQuery
     * @throws \Throwable
     */
    private function prepareTasksQuery()
    {
        $tasks = Task::find()
            ->where(['tasks.status' => $this->status ?? Task::STATUS_NEW])
            ->joinWith('category')
            ->joinWith('city')
            ->orderBy('created_at DESC');

        $cookies = Yii::$app->request->cookies;
        $city = Yii::$app->user->getIdentity()->city_id;
        if ($cookies->has('selected_city')) {
            $city = $cookies->get('selected_city');
        }

        if ($city !== null) {
            $tasks->andWhere(['or', ['city_id' => $city], ['city_id' => null]]);
        }

        if ($this->remote) {
            $tasks->andWhere('address is null');
        }

        if ($this->withoutApplications) {
            $tasks->joinWith('applications')
                ->having('count(applications.id) < 1')
                ->groupBy('tasks.id');
        }


        if ($this->period) {
            $date = (new \DateTimeImmutable())->sub(
                \DateInterval::createFromDateString($this->getInterval($this->period))
            )->format('Y-m-d');
            $tasks->andWhere( ['>=', 'created_at', $date]);
        }

        $tasks->andFilterWhere(['like', 'title', $this->searchName])
            ->andFilterWhere(['in', 'category_id', $this->categories]);

        return $tasks;
    }

    /**
     * Карта соответствий для интервала
     * @param $period
     * @return string
     */
    private function getInterval($period)
    {
        switch ($period) {
            case 'week':
                return '-1 week';
            case 'month':
                return '-1 month';
            default:
                return '-1 day';
        }
    }
}
