<?php

namespace frontend\controllers;

use frontend\models\Application;
use frontend\models\Feed;
use frontend\models\requests\ApplicationCreateForm;
use frontend\models\requests\ApplicationDoneForm;
use frontend\models\Task;
use frontend\services\ApplicationsService;
use Htmlacademy\Enums\Actions;
use Htmlacademy\Models\TaskStateMachine;
use Yii;
use yii\helpers\Url;

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
}
