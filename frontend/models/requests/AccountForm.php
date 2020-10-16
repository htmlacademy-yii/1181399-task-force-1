<?php

namespace frontend\models\requests;

use frontend\models\Attachment;
use frontend\models\Category;
use frontend\models\User;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class AccountForm extends Model
{
    public $email;
    public $city_id;
    public $birthday;
    public $description;

    public $specializations;

    public $password;
    public $password_confirmation;

    public $file;

    public $phone;
    public $skype;
    public $telegram;

    public $notifications_message;
    public $notifications_actions;
    public $notifications_feedback;
    public $hide_contacts;
    public $hide_profile;

    public $avatar;

    public function rules()
    {
        return [
            [['email', 'city_id', 'birthday', 'description', 'specializations', 'password', 'password_confirmation', 'phone', 'skype', 'telegram'], 'safe'],
            [['description', 'phone', 'skype', 'telegram'], 'string'],
            [['email'], 'email'],
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 6],
            [['avatar'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 1],
            [['password', 'password_confirmation'], 'string', 'min' => 8, 'max' => 40, 'skipOnEmpty' => true],
            [['password_confirmation'], 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли должны совпадать', 'skipOnEmpty' => true],
            ['birthday', 'date', 'format' => 'Y-m-d'],
            [['specializations'], 'each', 'rule' => ['exist', 'targetClass' => Category::class, 'targetAttribute' => 'id']],
            [['notifications_message', 'notifications_actions', 'notifications_feedback', 'hide_contacts', 'hide_profile'], 'boolean'],
        ];
    }

    public function save()
    {
        // У нас ajax запрос на добавление фотографий.
        // Чтобы этот запрос не повлиял на остальные модули - сделаем так.
        if (isset($this->files)) {
            $this->uploadPhotoes();
            return;
        }

        $this->updateProfileInfo();
        $this->updatePassword();
        $this->updateAvatar();
        $this->updateSpecializations();
        $this->updateNotificationSettings();
    }

    private function updateProfileInfo()
    {

        /** @var User $user */
        $user = Yii::$app->user->getIdentity();
        $user->birthday = $this->birthday;
        $user->email = $this->email;
        $user->description = $this->description;
        $user->city_id = $this->city_id;

        $user->phone = $this->phone;
        $user->telegram = $this->telegram;
        $user->skype = $this->skype;

        $user->save();
    }

    private function updatePassword()
    {
        if (isset($this->password, $this->password_confirmation)) {
            /** @var User $user */
            $user = Yii::$app->user->getIdentity();
            $user->password = Yii::$app->security->generatePasswordHash($this->password);
            $user->save();
        }
    }

    private function updateAvatar()
    {
        // Тут файла может попросту не быть. Поэтому так.
        if (isset($this->avatar)) {
            $file = UploadedFile::getInstance($this, 'avatar');
            if (!$file) {
                return;
            }

            $randomName = Yii::$app->security->generateRandomString(12);
            $name = "uploads/{$randomName}.{$file->extension}";
            $file->saveAs($name);

            /** @var User $user */
            $user = Yii::$app->user->getIdentity();
            $user->avatar_url = $name;
            $user->save();
        }
    }

    private function updateNotificationSettings()
    {
        /** @var User $user */
        $user = Yii::$app->user->getIdentity();
        $user->notification_actions = $this->notifications_actions;
        $user->notification_feedback = $this->notifications_actions;
        $user->notification_message = $this->notifications_message;

        $user->public_profile = $this->hide_profile;
        $user->public_contacts = $this->hide_contacts;

        $user->save();
    }

    private function updateSpecializations()
    {
        $categories = Category::findAll($this->specializations);

        /** @var User $user */
        $user = Yii::$app->user->getIdentity();
        $user->unlinkAll('categories', true);
        if (count($categories)> 0) {
            foreach ($categories as $category) {
                $user->link('categories', $category);
            }
        }
        $user->save();
    }

    private function uploadPhotoes()
    {
        $files = UploadedFile::getInstances($this, 'file');
        if (!$files) {
            return;
        }

        /** @var User $user */
        $user = Yii::$app->user->getIdentity();
        $user->unlinkAll('attachments', true);

        foreach ($files as $file) {
            $randomName = Yii::$app->security->generateRandomString(12);
            $name = "uploads/{$randomName}.{$file->extension}";
            $file->saveAs($name);

            $attachment = new Attachment();
            $attachment->url = $name;
            $attachment->name = $file->name;
            $attachment->save();

            $user->link('attachments', $attachment);
        }
    }
}
