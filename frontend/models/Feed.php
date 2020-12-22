<?php

namespace frontend\models;

/**
 * This is the model class for table "feed".
 *
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property int $task_id
 * @property string $description
 *
 * @property Task $task
 * @property User $user
 */
class Feed extends \yii\db\ActiveRecord
{
    const APPLICATION = 'application';
    const CHAT = 'chat';
    const REJECT = 'reject';
    const START = 'start';
    const END = 'end';
    const FEEDBACK = 'feedback';

    const TITLES = [
        'application' => 'У вас новая заявка!',
        'chat' => 'Новое сообщение в чате!',
        'reject' => 'Ваша заявка была отклонена!',
        'start' => 'Задание началось!',
        'end' => 'Задание окончено!',
        'feedback' => 'У вас новая обратная связь!'
    ];

    const EVENT_TYPES = [
        'application' => 'notification_actions',
        'chat' => 'notification_message',
        'reject' => 'notification_actions',
        'start' => 'notification_actions',
        'end' => 'notification_actions',
        'feedback' => 'notification_feedback',
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'feed';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'type', 'task_id', 'description'], 'required'],
            [['user_id', 'task_id'], 'integer'],
            [['type', 'description'], 'string'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'type' => 'Type',
            'task_id' => 'Task ID',
            'description' => 'Description',
        ];
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery|TasksQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|UsersQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return FeedQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FeedQuery(get_called_class());
    }
}
