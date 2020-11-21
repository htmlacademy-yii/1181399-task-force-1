<?php

namespace frontend\models;

/**
 * This is the ActiveQuery class for [[CategoryUser]].
 *
 * @see CategoryUser
 */
class CategoryUserQuery extends \yii\db\ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return CategoryUser[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return CategoryUser|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
