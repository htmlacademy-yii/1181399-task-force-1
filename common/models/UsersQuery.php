<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[User]].
 *
 * @see User
 */
class UsersQuery extends \yii\db\ActiveQuery
{

    /**
     * {@inheritdoc}
     * @return User[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return User|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
