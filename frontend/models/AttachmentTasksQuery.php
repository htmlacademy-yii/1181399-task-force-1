<?php

namespace frontend\models;

/**
 * This is the ActiveQuery class for [[AttachmentTask]].
 *
 * @see AttachmentTask
 */
class AttachmentTasksQuery extends \yii\db\ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return AttachmentTask[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return AttachmentTask|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
