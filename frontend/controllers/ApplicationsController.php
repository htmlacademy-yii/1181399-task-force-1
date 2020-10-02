<?php

namespace frontend\controllers;

use frontend\models\Application;
use frontend\models\requests\ApplicationCreateForm;
use frontend\models\requests\ApplicationDoneForm;
use frontend\models\Task;
use frontend\services\ApplicationsService;
use Yii;
use yii\base\InlineAction;

class ApplicationsController extends SecuredController
{
    public function actionCreate()
    {
        $model = new ApplicationCreateForm();
        $model->load(Yii::$app->request->post());
        if (Yii::$app->request->isPost) {
            $model->create();
        }
        return $this->redirect(['tasks/view', 'id' => $model->task_id]);
    }

    public function actionAccept($applicationId)
    {
        $application = Application::find()->where(['id' => $applicationId])->one();

        if (!$application) {
            $this->goBack();
        }

        $applicationService = new ApplicationsService();
        $applicationService->accept($application);

        return $this->redirect(['tasks/view', 'id' => $application->task->id]);
    }

    public function actionReject($applicationId)
    {
        $application = Application::find()->where(['id' => $applicationId])->one();

        if (!$application) {
            $this->redirect(['tasks/view', 'id' => $application->task->id]);
        }

        $application->status = Application::STATUS_DECLINED;
        $application->save();

        return $this->redirect(['tasks/view', 'id' => $application->task->id]);
    }

    public function actionDone()
    {
        $model = new ApplicationDoneForm();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->finishTask();
            return $this->redirect(['tasks/view', 'id' => $model->taskId]);
        }

        return $this->goBack();
    }

    public function actionFail($taskId)
    {
        $applicationService = new ApplicationsService();
        $applicationService->fail($taskId, Yii::$app->user->getId());

        return $this->redirect(['tasks/view', 'id' => $taskId]);
    }

    public function behaviors()
    {
        $rules = parent::behaviors();
        $newRules = [
            [
                'allow' => true,
                'actions' => ['create'],
                'matchCallback' => function ($rule, $action) {
                    $user = Yii::$app->user->getIdentity();
                    return !$user->isAuthor();
                }
            ],
            [
                'allow' => true,
                'actions' => ['accept', 'reject'],
                'matchCallback' => function ($rule, $action) {
                    $user = Yii::$app->user->getId();
                    $application = Application::findOne([
                         'id' => Yii::$app->request->queryParams['applicationId']
                    ]);

                    if (!$application) {
                        return false;
                    }

                    if ($user === $application->task->author_id) {
                        return true;
                    }

                    return false;
                }
            ],
            [
                'allow' => true,
                'actions' => ['done'],
                'matchCallback' => function ($rule, $action) {
                    $user = Yii::$app->user->getId();
                    $task = Task::findOne([
                         'id' => Yii::$app->request->post('task_id') ?? 0
                    ]);

                    if (!$task) {
                        return false;
                    }

                    if ($user === $task->author_id) {
                        return true;
                    }

                    return false;
                }
            ],
            [
                'allow' => true,
                'actions' => ['fail'],
                'matchCallback' => function ($rule, $action) {
                    $user = Yii::$app->user->getId();
                    $task = Task::findOne([
                         'id' => Yii::$app->request->getQueryParam('task_id') ?? 0
                    ]);

                    if (!$task) {
                        return false;
                    }

                    if ($user === $task->author_id) {
                        return true;
                    }

                    return false;
                }
            ],
        ];

        foreach ($newRules as $rule) {
            array_unshift($rules['access']['rules'], $rule);
        }

        return $rules;
    }
}