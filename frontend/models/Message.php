<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "messages".
 *
 * @property int $id
 * @property int $author_id
 * @property int $recipient_id
 * @property int $task_id
 * @property string $content
 * @property int|null $is_read
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property User $author
 * @property User $recipient
 * @property Task $task
 */
class Message extends \yii\db\ActiveRecord
{
    public $message;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'messages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['author_id', 'recipient_id', 'task_id', 'content'], 'required'],
            [['author_id', 'recipient_id', 'task_id', 'is_read'], 'integer'],
            [['content'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['author_id'], 'exist', 'skipOnError' => false, 'targetClass' => User::class, 'targetAttribute' => 'id'],
            [['recipient_id'], 'exist', 'skipOnError' => false, 'targetClass' => User::class, 'targetAttribute' => 'id'],
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
            'author_id' => 'Author ID',
            'recipient_id' => 'Recipient ID',
            'task_id' => 'Task ID',
            'content' => 'Content',
            'is_read' => 'Is Read',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery|UsersQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }

    /**
     * Gets query for [[Recipient]].
     *
     * @return \yii\db\ActiveQuery|UsersQuery
     */
    public function getRecipient()
    {
        return $this->hasOne(User::class, ['id' => 'recipient_id']);
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
     * {@inheritdoc}
     * @return MessagesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MessagesQuery(get_called_class());
    }

    public function fields()
    {
        return [
            'message' => 'content',
            'published_at' => 'created_at',
            'is_mine' => function () {
                return $this->author_id === Yii::$app->user->getId();
            }
        ];
    }

    public function beforeValidate()
    {

        if (null !== Yii::$app->request->post('message')) {
            $this->content = Yii::$app->request->post('message');
        }

        if (isset($this->task_id)) {
            $authorId = Yii::$app->user->getId();
            $this->author_id = $authorId;
            $this->recipient_id = $authorId === $this->task->author_id ? $this->task->author_id : $this->task->executor_id;
        }

        return parent::beforeValidate();
    }
}
