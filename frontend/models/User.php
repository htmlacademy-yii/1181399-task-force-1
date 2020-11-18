<?php

namespace frontend\models;

use DateTime;
use voskobovich\linker\LinkerBehavior;
use Yii;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;
use yii\web\UploadedFile;

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
 * @property Category[] $categories
 * @property City $city
 * @property Feedback[] $feedbacks
 * @property Feedback[] $selfFeedbacks
 * @property Feed[] $feeds
 * @property Message[] $messages
 * @property Message[] $messages0
 * @property Task[] $tasks
 * @property Task[] $tasks0
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{

    public $tasks_count;
    public $feedback_count;
    public $rating;

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
            [['name', 'email'], 'required'],
            [['birthday', 'last_visit', 'created_at', 'updated_at'], 'safe'],
            [['description', 'avatar_url', 'address'], 'string'],
            [
                [
                    'notification_message',
                    'notification_actions',
                    'notification_feedback',
                    'private_contacts',
                    'private_profile'
                ],
                'boolean'
            ],
            [['name', 'email', 'password'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 20],
            [['skype', 'telegram'], 'string', 'max' => 50],
            [
                ['city_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => City::class,
                'targetAttribute' => ['city_id' => 'id']
            ],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => LinkerBehavior::class,
                'relations' => [
                    'categories_ids' => 'categories'
                ],
            ],
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
            'password' => 'Пароль',
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
     * Gets query for [[self_feedbacks]].
     *
     * @return \yii\db\ActiveQuery|FeedbackQuery
     */
    public function getSelfFeedbacks()
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

    public function getTasksCount()
    {
        return $this->hasMany(Task::class, ['author_id' => 'id'])->count();
    }

    /**
     * Gets query for [[Tasks0]].
     *
     * @return \yii\db\ActiveQuery|TasksQuery
     */
    public function getExecutorTasks()
    {
        return $this->hasMany(Task::class, ['executor_id' => 'id']);
    }

    public function getCategories()
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])
            ->viaTable('category_user', ['user_id' => 'id']);
    }

    public function getRatingSum()
    {
        return $this->hasMany(Feedback::class, ['user_id' => 'id'])->sum('rating');
    }

    /**
     * {@inheritdoc}
     * @return UsersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UsersQuery(get_called_class());
    }

    public function isOnline()
    {
        $lastVisit = new \DateTimeImmutable($this->last_visit);
        $interval = $lastVisit->diff(new \DateTimeImmutable());
        return $interval->i + $interval->h * 60 + $interval->d * 24 * 60 < 5;
    }

    public function getAge()
    {
        return (new \DateTime())->diff(new \DateTime($this->birthday))->y;
    }

    public function getAttachments()
    {
        return $this->hasMany(Attachment::class, ['id' => 'attachment_id'])
            ->viaTable('attachment_user', ['user_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return \common\models\User|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['email' => $username]);
    }


    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        if (!$password) {
            return false;
        }

        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    public function getPasswordHash()
    {
        return $this->password;
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function isAuthor()
    {
        return count($this->categoryUsers) === 0;
    }

    public function applied($task_id)
    {
        return Application::find()->where(['task_id' => $task_id, 'user_id' => $this->id])->count() > 0;
    }

    public function age()
    {
        $datetime1 = new DateTime($this->birthday);

        $datetime2 = new DateTime();

        $diff = $datetime1->diff($datetime2);

        return $diff->y;
    }


    public function uploadPhotos()
    {
        $files = UploadedFile::getInstancesByName('file');

        if (!$files) {
            return;
        }

        $this->unlinkAll('attachments', true);

        foreach ($files as $file) {
            $randomName = Yii::$app->security->generateRandomString(12);
            $name = "uploads/{$randomName}.{$file->extension}";
            $file->saveAs($name);

            $attachment = new Attachment();
            $attachment->url = $name;
            $attachment->name = $file->name;
            $attachment->save();

            $this->link('attachments', $attachment);
        }
    }

    public function shouldRecieve(string $event)
    {
        return isset(Feed::EVENT_TYPES[$event]) && $this->{Feed::EVENT_TYPES[$event]} == true;
    }
}
