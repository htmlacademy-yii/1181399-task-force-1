<?php

namespace frontend\models\requests;

use frontend\models\Attachment;
use frontend\models\Category;
use frontend\models\User;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class AccountForm extends User
{
    public $new_password;
    public $password_confirmation;

    public $file;

    /**
     * @var UploadedFile
     */
    public $avatar;

    public function rules()
    {
        return [
            [['email', 'city_id', 'birthday', 'description', 'specializations', 'password', 'password_confirmation',
                'phone', 'skype', 'telegram', 'notification_message', 'notification_actions', 'notification_feedback',
                'private_contacts', 'private_profile', 'categories_ids'], 'safe'],
            [['email'], 'email'],
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 6],
            [['avatar'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 1],
            [['new_password'], 'string', 'min' => 8, 'max' => 40, 'skipOnEmpty' => true],
            [['password_confirmation'], 'compare', 'compareAttribute' => 'new_password', 'message' => 'Пароли должны совпадать', 'skipOnEmpty' => true],
            ['birthday', 'date', 'format' => 'Y-m-d'],
        ];
    }

    public function beforeSave($insert)
    {
        $this->updatePassword();
        $this->updateAvatar();

        return true;
    }


    private function updatePassword()
    {
        if ($this->new_password) {
            $this->password = Yii::$app->security->generatePasswordHash($this->new_password);
            $this->save();
        }
    }

    private function updateAvatar()
    {
        $file = UploadedFile::getInstance($this, 'avatar');

        if (!$file) {
            return;
        }

        $randomName = Yii::$app->security->generateRandomString(12);
        $name = "uploads/{$randomName}.{$file->extension}";
        $file->saveAs($name);

        $this->avatar_url = $name;
    }
}
