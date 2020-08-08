<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "attachments".
 *
 * @property int $id
 * @property string $url
 * @property string $name
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property AttachmentTask[] $attachmentTasks
 * @property AttachmentUser[] $attachmentUsers
 */
class Attachment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'attachments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url', 'name'], 'required'],
            [['url'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'name' => 'Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[AttachmentTasks]].
     *
     * @return \yii\db\ActiveQuery|AttachmentTasksQuery
     */
    public function getAttachmentTasks()
    {
        return $this->hasMany(AttachmentTask::class, ['attachment_id' => 'id']);
    }

    /**
     * Gets query for [[AttachmentUsers]].
     *
     * @return \yii\db\ActiveQuery|AttachmentUserQuery
     */
    public function getAttachmentUsers()
    {
        return $this->hasMany(AttachmentUser::class, ['attachment_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return AttachmentsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AttachmentsQuery(get_called_class());
    }
}
