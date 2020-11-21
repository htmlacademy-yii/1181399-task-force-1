<?php

namespace frontend\models;

use Htmlacademy\Models\TaskStateMachine;
use Yii;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property int $author_id
 * @property int|null $executor_id
 * @property int $category_id
 * @property int $city_id
 * @property int|null $budget
 * @property string|null $description
 * @property string $title
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string $until
 * @property string|null $status
 * @property string $address
 * @property string|null $address_comment
 * @property string|null $map_w
 * @property string|null $map_h
 *
 * @property Application[] $applications
 * @property Application[] $visibleApplications
 * @property AttachmentTask[] $attachmentTasks
 * @property User $author
 * @property Category $category
 * @property City $city
 * @property User $executor
 * @property Feedback[] $feedbacks
 * @property Feed[] $feeds
 * @property Message[] $messages
 */
class Task extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 'new';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['author_id', 'category_id', 'title'], 'required'],
            [['author_id', 'executor_id', 'category_id', 'city_id', 'budget'], 'integer'],
            [['description', 'status', 'address', 'address_comment'], 'string'],
            [['created_at', 'updated_at', 'until'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['map_w', 'map_h'], 'string', 'max' => 15],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author_id' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['executor_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
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
            'executor_id' => 'Executor ID',
            'category_id' => 'Category ID',
            'city_id' => 'City ID',
            'budget' => 'Budget',
            'description' => 'Description',
            'title' => 'Title',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'until' => 'Until',
            'status' => 'Status',
            'address' => 'Address',
            'address_comment' => 'Address Comment',
            'map_w' => 'Map W',
            'map_h' => 'Map H',
        ];
    }

    /**
     * Gets query for [[Applications]].
     *
     * @return \yii\db\ActiveQuery|ApplicationsQuery
     */
    public function getApplications()
    {
        return $this->hasMany(Application::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Applications]].
     *
     * @return \yii\db\ActiveQuery|ApplicationsQuery
     */
    public function getVisibleApplications()
    {
        return $this->getApplications()->where(['in', 'status', ['accepted', 'new']]);
    }

    /**
     * Gets query for [[AttachmentTasks]].
     *
     * @return \yii\db\ActiveQuery|AttachmentTasksQuery
     */
    public function getAttachmentTasks()
    {
        return $this->hasMany(AttachmentTask::class, ['task_id' => 'id']);
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
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery|CategoriesQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery|CitiesQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Executor]].
     *
     * @return \yii\db\ActiveQuery|UsersQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(User::class, ['id' => 'executor_id']);
    }

    /**
     * Gets query for [[Feedbacks]].
     *
     * @return \yii\db\ActiveQuery|FeedbackQuery
     */
    public function getFeedbacks()
    {
        return $this->hasOne(Feedback::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Feeds]].
     *
     * @return \yii\db\ActiveQuery|FeedQuery
     */
    public function getFeeds()
    {
        return $this->hasMany(Feed::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery|MessagesQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::class, ['task_id' => 'id']);
    }

    public function getApplicationsCount()
    {
        return $this->hasMany(Application::class, ['task_id' => 'id'])->count();
    }

    public function getAttachments()
    {
        return $this->hasMany(Attachment::class, ['id' => 'attachment_id'])
            ->viaTable('attachment_task', ['task_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return TasksQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TasksQuery(get_called_class());
    }

    public function fields()
    {
        return [
            'id',
            'author_name' => function () {
                return $this->author->name;
            },
            'new_messages' => function() {
                return count($this->messages);
            },
            'published_at' => 'created_at',
            'title'
        ];
    }

    public function getStatusName()
    {
        return TaskStateMachine::STATUS_NAMES[$this->status] ?? 'Ошибка статуса';
    }
}
