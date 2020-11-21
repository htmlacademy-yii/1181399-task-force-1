<?php

namespace frontend\models;

/**
 * This is the ActiveQuery class for [[Attachments]].
 *
 * @see Attachment
 */
class AttachmentsQuery extends \yii\db\ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return Attachment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Attachment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
