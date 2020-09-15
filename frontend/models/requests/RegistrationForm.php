<?php

namespace frontend\models\requests;

use frontend\models\Task;
use frontend\models\User;
use yii\base\Model;

class RegistrationForm extends Model
{
    public $email;
    public $name;
    public $city;
    public $password;

    public function rules()
    {
        return [
            [['email', 'name', 'city', 'password'], 'safe'],
            [['email', 'name', 'password'], 'string', 'max' => 100],
            [['email'], 'email'],
            [['email'], 'unique', 'targetClass' => User::class],

        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'E-mail',
            'name' => 'Имя',
            'city' => 'Город',
            'password' => 'Пароль',
        ];
    }
}
