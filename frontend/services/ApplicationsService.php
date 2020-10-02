<?php

namespace frontend\services;

use frontend\models\Application;
use frontend\models\Task;
use Htmlacademy\Enums\Actions;
use Htmlacademy\Models\TaskStateMachine;

class ApplicationsService
{
    public function fail(int $taskId, ?int $userId): bool
    {
        $task = Task::findOne(['id' => $taskId]);
        if (!$task) {
            return false;
        }

        if ($task->executor_id === $userId && $task->status === TaskStateMachine::STATUS_WIP) {
            $task->status = TaskStateMachine::STATUS_FAILED;
            return $task->save();
        }

        return false;
    }

    public function accept(Application $application)
    {
        $stateMachine = new TaskStateMachine($application->task->executor_id, $application->task->author_id);
        $stateMachine->setStatus($application->task->status);
        $task = $application->task;

        if ($status = $stateMachine->getNextStatus(Actions::ACCEPT)) {
            $task->status = $status;
            $task->executor_id = $application->user_id;
            $task->save();

            $this->declineAnotherApplications($task->applications, $application->id);
        }
    }

    private function declineAnotherApplications($applications, $wonId)
    {
        foreach ($applications as $application) {
            if ($application->id == $wonId) {
                $application->status = Application::STATUS_ACCEPTED;
                $application->save();
                continue;
            }
            $application->status = Application::STATUS_DECLINED;
            $application->save();
        }
    }
}
