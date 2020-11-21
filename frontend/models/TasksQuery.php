<?php

namespace frontend\models;

/**
 * This is the ActiveQuery class for [[Task]].
 *
 * @see TaskStateMachine
 */
class TasksQuery extends \yii\db\ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return Task[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Task|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
