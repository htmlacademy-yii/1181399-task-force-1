<?php

namespace frontend\controllers;

use frontend\models\Category;
use frontend\models\requests\ApplicationCreateForm;
use frontend\models\requests\ApplicationDoneForm;
use frontend\models\requests\TaskCreateForm;
use frontend\models\requests\TasksSearchForm;
use frontend\models\Task;
use Htmlacademy\Models\TaskStateMachine;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class TasksController extends SecuredController
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

        $applicationForm = new ApplicationCreateForm();
        $applicationForm->load(Yii::$app->request->post());

        $applicationDoneForm = new ApplicationDoneForm();
        $applicationDoneForm->load(Yii::$app->request->post());

        $taskStateMachine = new TaskStateMachine($task->executor_id, $task->author_id);
        $taskStateMachine->setStatus($task->status);

        return $this->render('show',
             [
                 'task' => $task,
                 'applicationModel' => $applicationForm,
                 'doneModel' => $applicationDoneForm,
                 'taskStateMachine' => $taskStateMachine,
             ]
        );
    }

    public function actionCreate()
    {
        $model = new TaskCreateForm();
        $model->load(Yii::$app->request->post());

        if (Yii::$app->request->isPost) {
            if ($id = $model->saveTask()) {
                return $this->redirect(['tasks/view', 'id' => $id]);
            }
        }

        $categories = Category::find()->all();
        return $this->render('create', compact('categories', 'model'));
    }

    public function behaviors()
    {
        $rules = parent::behaviors();
        $rule = [
            [
                'allow' => false,
                'roles' => ['?']
            ],
            [
                'allow' => false,
                'actions' => ['create'],
                'roles' => ['@'],
                'matchCallback' => function ($rule, $action) {
                    $user = Yii::$app->user->getIdentity();
                    return !$user->isAuthor();
                }
            ],
            [
                'allow' => true,
                'roles' => ['@']
            ]
        ];
        array_unshift($rules['access']['rules'], ...$rule);

        return $rules;
    }
}
