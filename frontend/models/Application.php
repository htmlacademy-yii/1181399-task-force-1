<?php

namespace frontend\models;

/**
 * This is the model class for table "applications".
 *
 * @property int $id
 * @property int $user_id
 * @property int $task_id
 * @property int $budget
 * @property string|null $comment
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $status
 *
 * @property Task $task
 * @property User $user
 */
class Application extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 'new';
    const STATUS_DECLINED = 'declined';
    const STATUS_ACCEPTED = 'accepted';


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'applications';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'task_id', 'budget'], 'required'],
            [['user_id', 'task_id', 'budget'], 'integer'],
            [['comment', 'status'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
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
            'task_id' => 'Task ID',
            'budget' => 'Budget',
            'comment' => 'Comment',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
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
     * @return ApplicationsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ApplicationsQuery(get_called_class());
    }
}
