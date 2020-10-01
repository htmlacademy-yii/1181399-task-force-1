<?php

namespace frontend\controllers;

use frontend\models\Application;
use frontend\models\Feed;
use frontend\models\requests\ApplicationCreateForm;
use frontend\models\requests\ApplicationDoneForm;
use frontend\models\Task;
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
        return $this->redirect(Url::to(['tasks/view', 'id' => $model->task_id]));
    }

    public function actionAccept($applicationId)
    {
        $application = Application::find()->where(['id' => $applicationId])->one();
        $stateMachine = new TaskStateMachine($application->task->executor_id, $application->task->author_id);
        $stateMachine->setStatus($application->task->status);
        $task = $application->task;

        if ($status = $stateMachine->getNextStatus(Actions::ACCEPT)) {
            $task->status = $status;
            $task->executor_id = $application->user_id;
            $task->save();

            $this->setAllApplicationDeclined($task->applications, $applicationId);
        }

        return $this->redirect(Url::to(['tasks/view', 'id' => $task->id]));
    }

    public function actionReject($applicationId)
    {
        $application = Application::find()->where(['id' => $applicationId])->one();
        $application->status = 'declined';
        $application->save();

        return $this->redirect(Url::to(['tasks/view', 'id' => $application->task->id]));
    }

    public function actionDone()
    {
        $model = new ApplicationDoneForm();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->finishTask();
        }

        return $this->goBack();
    }

    public function actionFail($taskId)
    {
        $task = Task::findOne(['id' => $taskId]);
        if (!$task) {
            $this->goBack();
        }

        if ($task->executor_id === Yii::$app->user->getId() && $task->status === 'wip') {
            $task->status = 'failed';
            $task->save();
        }

        return $this->redirect(Url::to(['tasks/view', 'id' => $task->id]));
    }

    private function setAllApplicationDeclined($applications, $wonId)
    {
        foreach ($applications as $application) {
            if ($application->id == $wonId) {
                $application->status = 'accepted';
                $application->save();
                continue;
            }
            $application->status = 'declined';
            $application->save();
        }
    }
}
