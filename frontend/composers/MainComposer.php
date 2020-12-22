<?php

namespace frontend\composers;

use frontend\models\City;
use frontend\models\Feed;

class MainComposer
{
    /**
     * Возвращает уведомления для пользователя
     * @return array|Feed[]
     */
    public function getNotifications()
    {
        if (\Yii::$app->user->isGuest) {
            return [];
        }

        return Feed::find()->where(['user_id' => \Yii::$app->user->getId()])->all();
    }

    /**
     * Возвращает города
     * @return array|City[]
     */
    public function getCities()
    {
        return City::find()->all();
    }
}
