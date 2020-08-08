<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $name
 * @property string $birthday
 * @property string|null $description
 * @property string $email
 * @property string $password
 * @property string|null $phone
 * @property string|null $skype
 * @property string|null $telegram
 * @property string|null $avatar_url
 * @property string|null $last_visit
 * @property int $city_id
 * @property string|null $address
 * @property int|null $notification_message
 * @property int|null $notification_actions
 * @property int|null $notification_feedback
 * @property int|null $public_contacts
 * @property int|null $public_profile
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Application[] $applications
 * @property AttachmentUser[] $attachmentUsers
 * @property Bookmark[] $bookmarks
 * @property Bookmark[] $bookmarks0
 * @property CategoryUser[] $categoryUsers
 * @property City $city
 * @property Feedback[] $feedbacks
 * @property Feedback[] $feedbacks0
 * @property Feed[] $feeds
 * @property Message[] $messages
 * @property Message[] $messages0
 * @property Task[] $tasks
 * @property Task[] $tasks0
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'birthday', 'email', 'password', 'city_id'], 'required'],
            [['birthday', 'last_visit', 'created_at', 'updated_at'], 'safe'],
            [['description', 'avatar_url', 'address'], 'string'],
            [['city_id', 'notification_message', 'notification_actions', 'notification_feedback', 'public_contacts', 'public_profile'], 'integer'],
            [['name', 'email', 'password'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 20],
            [['skype', 'telegram'], 'string', 'max' => 50],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'birthday' => 'Birthday',
            'description' => 'Description',
            'email' => 'Email',
            'password' => 'Password',
            'phone' => 'Phone',
            'skype' => 'Skype',
            'telegram' => 'Telegram',
            'avatar_url' => 'Avatar Url',
            'last_visit' => 'Last Visit',
            'city_id' => 'City ID',
            'address' => 'Address',
            'notification_message' => 'Notification Message',
            'notification_actions' => 'Notification Actions',
            'notification_feedback' => 'Notification Feedback',
            'public_contacts' => 'Public Contacts',
            'public_profile' => 'Public Profile',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Applications]].
     *
     * @return \yii\db\ActiveQuery|ApplicationsQuery
     */
    public function getApplications()
    {
        return $this->hasMany(Application::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[AttachmentUsers]].
     *
     * @return \yii\db\ActiveQuery|AttachmentUsersQuery
     */
    public function getAttachmentUsers()
    {
        return $this->hasMany(AttachmentUser::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Bookmarks]].
     *
     * @return \yii\db\ActiveQuery|BookmarksQuery
     */
    public function getBookmarks()
    {
        return $this->hasMany(Bookmark::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Bookmarks0]].
     *
     * @return \yii\db\ActiveQuery|BookmarksQuery
     */
    public function getBookmarks0()
    {
        return $this->hasMany(Bookmark::class, ['bookmark_user_id' => 'id']);
    }

    /**
     * Gets query for [[CategoryUsers]].
     *
     * @return \yii\db\ActiveQuery|CategoryUsersQuery
     */
    public function getCategoryUsers()
    {
        return $this->hasMany(CategoryUser::class, ['user_id' => 'id']);
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
     * Gets query for [[Feedbacks]].
     *
     * @return \yii\db\ActiveQuery|FeedbackQuery
     */
    public function getFeedbacks()
    {
        return $this->hasMany(Feedback::class, ['author_id' => 'id']);
    }

    /**
     * Gets query for [[Feedbacks0]].
     *
     * @return \yii\db\ActiveQuery|FeedbackQuery
     */
    public function getFeedbacks0()
    {
        return $this->hasMany(Feedback::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Feeds]].
     *
     * @return \yii\db\ActiveQuery|FeedQuery
     */
    public function getFeeds()
    {
        return $this->hasMany(Feed::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery|MessagesQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::class, ['author_id' => 'id']);
    }

    /**
     * Gets query for [[Messages0]].
     *
     * @return \yii\db\ActiveQuery|MessagesQuery
     */
    public function getMessages0()
    {
        return $this->hasMany(Message::class, ['recipient_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery|TasksQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::class, ['author_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks0]].
     *
     * @return \yii\db\ActiveQuery|TasksQuery
     */
    public function getTasks0()
    {
        return $this->hasMany(Task::class, ['executor_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return UsersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UsersQuery(get_called_class());
    }
}
