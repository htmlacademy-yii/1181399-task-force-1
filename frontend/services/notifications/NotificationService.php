<?php

namespace frontend\services\notifications;

use frontend\models\Feed;
use frontend\models\User;

class NotificationService
{
    public function notify(User $user, string $event, int $taskId)
    {
        if (!$user->shouldRecieve($event)) {
            return;
        }

        $this->sendEmail($user, Feed::TITLES[$event], $event, $taskId);
        $this->addNotification($user, Feed::TITLES[$event], $event, $taskId);
    }

    private function sendEmail(User $user, string $message, string $event, int $taskId)
    {
        \Yii::$app->mailer->compose('mail/notification', ['content' => $message, 'task' => $taskId])
            ->setFrom('mail@example.com')
            ->setTo($user->email)
            ->setSubject(Feed::TITLES[$event] ?? 'Вам новое уведомление')
            ->send();
    }

    private function addNotification(User $user, string $message, string $event, int $taskId)
    {
        $feed = new Feed();
        $feed->user_id = $user->getId();
        $feed->task_id = $taskId;
        $feed->type = $event;
        $feed->description = $message;
        $feed->save();
    }
}
