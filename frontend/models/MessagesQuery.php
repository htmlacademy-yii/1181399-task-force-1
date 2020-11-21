<?php

namespace frontend\models;

/**
 * This is the ActiveQuery class for [[Message]].
 *
 * @see Message
 */
class MessagesQuery extends \yii\db\ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return Message[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Message|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
