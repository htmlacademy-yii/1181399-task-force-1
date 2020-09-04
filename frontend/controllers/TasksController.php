<?php

namespace frontend\controllers;

use Cassandra\Exception\ValidationException;
use DateInterval;
use frontend\models\Category;
use frontend\models\Task;
use Yii;
use frontend\models\requests\TasksSearchForm;
use yii\web\Controller;
use yii\widgets\ActiveForm;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $request = new TasksSearchForm();
        $request->load(Yii::$app->request->get());
        if (!$request->validate()) {
            return $this->redirect('/tasks');
        }

        $searchFields = [
            'status' => Task::STATUS_NEW,
            'remote' => $request->remote,
            'categories' => $request->categories,
            'period' => $request->period,
            'searchName' => $request->searchName,
            'withoutApplications' => $request->withoutApplications,
        ];

        $tasks = $this->getTasks($searchFields);
        $categories = Category::find()->all();

        return $this->render('browse', compact('tasks', 'categories', 'request'));
    }

    private function getTasks(array $searchFields)
    {
        $tasks = Task::find()
            ->where(['status' => $searchFields['status'] ?? Task::STATUS_NEW])
            ->joinWith('category')
            ->joinWith('city')
            ->orderBy('created_at DESC');

        if (isset($searchFields['remote']) && $searchFields['remote']) {
            $tasks->andWhere('address is null');
        }

        if (isset($searchFields['withoutApplications']) && $searchFields['withoutApplications']) {
            $tasks->leftJoin('applications', 'applications.task_id = id')
                ->having('count(applications.id) > 0')
                ->groupBy('tasks.id');
        }

        $name = $searchFields['searchName'] ?? '';
        if ($name && $name !== '') {
            $tasks->andWhere(['like', 'title', "%$name%"]);
        }

        if (isset($searchFields['period']) && $searchFields['period']) {
            $date = (new \DateTimeImmutable())->sub(
                DateInterval::createFromDateString($this->getInterval($searchFields['period']))
            )->format('Y-m-d');
            $tasks->andWhere( ['>=', 'created_at', $date]);
        }

        if (isset($searchFields['categories']) && $searchFields['categories']) {
            $tasks->andWhere(['in', 'category_id', $searchFields['categories']]);
        }
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
