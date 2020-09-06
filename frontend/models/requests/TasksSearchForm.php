<?php

namespace frontend\models\requests;

use frontend\models\Task;
use yii\base\Model;

class TasksSearchForm extends Model
{
    public $categories;
    public $withoutApplications;
    public $remote;
    public $period;
    public $searchName;

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

    public function getPeriods() {
        return [
            '' => 'Период',
            'day' => 'День',
            'week' => 'Неделя',
            'month' => 'Месяц',
        ];
    }

    public function getTasks()
    {
        $tasks = Task::find()
            ->where(['tasks.status' => $this->status ?? Task::STATUS_NEW])
            ->joinWith('category')
            ->joinWith('city')
            ->orderBy('created_at DESC');

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

        $tasks = $tasks->all();

        return $tasks;
    }

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
