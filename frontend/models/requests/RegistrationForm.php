<?php

namespace frontend\models\requests;

use frontend\models\City;
use frontend\models\Task;
use frontend\models\User;
use yii\base\Model;

class RegistrationForm extends Model
{
    public function rules()
    {
        return [
            [['email', 'name', 'city', 'password'], 'required'],
            [['email', 'name', 'city', 'password'], 'safe'],
            [['email', 'name', 'password'], 'string', 'max' => 100],
            [['email'], 'email'],
            [['email'], 'unique', 'targetClass' => User::class],
            [['city'], 'exist', 'targetClass' => City::class, 'targetAttribute' => 'id'],
            [['password'], 'string', 'min' => 8],

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
