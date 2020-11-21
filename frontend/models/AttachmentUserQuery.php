<?php

namespace frontend\models;

/**
 * This is the ActiveQuery class for [[AttachmentUser]].
 *
 * @see AttachmentUser
 */
class AttachmentUserQuery extends \yii\db\ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return AttachmentUser[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return AttachmentUser|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
