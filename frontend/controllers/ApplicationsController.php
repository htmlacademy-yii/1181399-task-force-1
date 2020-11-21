<?php

namespace frontend\controllers;

use frontend\models\Application;
use frontend\models\Feed;
use frontend\models\requests\ApplicationCreateForm;
use frontend\models\requests\ApplicationDoneForm;
use frontend\models\Task;
use frontend\services\ApplicationsService;
use frontend\services\notifications\NotificationService;
use Yii;
use yii\base\InlineAction;

class ApplicationsController extends SecuredController
{
    /**
     * Страница создания заявки на задание
     *
     * @return \yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ApplicationCreateForm();
        $model->load(Yii::$app->request->post());
        if (Yii::$app->request->isPost) {
            $model->create();
        }
        return $this->redirect(['tasks/view', 'id' => $model->task_id]);
    }

    /**
     * Принятие заявки
     *
     * @param $applicationId
     * @return \yii\web\Response
     * @throws \Throwable
     */
    public function actionAccept($applicationId)
    {
        $application = Application::find()->where(['id' => $applicationId])->one();

        if (!$application) {
            $this->goBack();
        }

        $applicationService = new ApplicationsService();
        $applicationService->accept($application);

        $notification = new NotificationService();
        $notification->notify(Yii::$app->user->getIdentity(), Feed::START, $application->task_id);

        return $this->redirect(['tasks/view', 'id' => $application->task->id]);
    }

    /**
     * Отклонение заявки
     *
     * @param $applicationId
     * @return \yii\web\Response
     * @throws \Throwable
     */
    public function actionReject($applicationId)
    {
        $application = Application::find()->where(['id' => $applicationId])->one();

        if (!$application) {
            $this->redirect(['tasks/view', 'id' => $application->task->id]);
        }

        $application->status = Application::STATUS_DECLINED;
        $application->save();

        $notification = new NotificationService();
        $notification->notify(Yii::$app->user->getIdentity(), Feed::REJECT, $application->task_id);

        return $this->redirect(['tasks/view', 'id' => $application->task->id]);
    }


    /**
     * Отметка заявки, как сделанное
     *
     * @return \yii\web\Response
     * @throws \Throwable
     */
    public function actionDone()
    {
        $model = new ApplicationDoneForm();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->finishTask();

            $notification = new NotificationService();
            $notification->notify(
                Yii::$app->user->getIdentity(),
                Feed::END,
                Yii::$app->request->post('task_id')
            );

            return $this->redirect(['tasks/view', 'id' => $model->taskId]);
        }

        return $this->goBack();
    }


    /**
     * Несдача заявки.
     *
     * @param $taskId
     * @return \yii\web\Response
     * @throws \Throwable
     */
    public function actionFail($taskId)
    {
        $applicationService = new ApplicationsService();
        $applicationService->fail($taskId, Yii::$app->user->getId());

        $notification = new NotificationService();
        $notification->notify(
            Yii::$app->user->getIdentity(),
            Feed::END,
            Yii::$app->request->post('task_id')
        );

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
