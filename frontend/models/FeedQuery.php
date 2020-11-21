<?php

namespace frontend\models;

/**
 * This is the ActiveQuery class for [[Feed]].
 *
 * @see Feed
 */
class FeedQuery extends \yii\db\ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return Feed[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Feed|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
