<?php

namespace frontend\composers;

use frontend\models\Feed;
use frontend\services\notifications\NotificationService;

class MainComposer
{
    public function getNotifications()
    {
        if (\Yii::$app->user->isGuest) {
            return [];
        }

        return Feed::find()->where(['user_id' => \Yii::$app->user->getId()])->all();
    }
}
