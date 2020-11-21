<?php

namespace frontend\services\notifications;

use frontend\models\Feed;
use frontend\models\User;

class NotificationService
{
    /**
     * Уведомляет пользователя о событии в определенном задании.
     * Список определяется в Feed::TITLES
     *
     * @param User $user
     * @param string $event
     * @param int $taskId
     */
    public function notify(User $user, string $event, int $taskId)
    {
        if (!$user->shouldRecieve($event)) {
            return;
        }

        $this->sendEmail($user, Feed::TITLES[$event], $event, $taskId);
        $this->addNotification($user, Feed::TITLES[$event], $event, $taskId);
    }

    /**
     * Отправка сообщения электронной почтой.
     *
     * @param User $user
     * @param string $message
     * @param string $event
     * @param int $taskId
     */
    private function sendEmail(User $user, string $message, string $event, int $taskId)
    {
        \Yii::$app->mailer->compose('@mail/notification', ['content' => $message, 'taskId' => $taskId])
            ->setFrom('mail@example.com')
            ->setTo($user->email)
            ->setSubject(Feed::TITLES[$event] ?? 'Вам новое уведомление')
            ->send();
    }

    /**
     * Отправка сообщения в ленту.
     *
     * @param User $user
     * @param string $message
     * @param string $event
     * @param int $taskId
     */
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
