<?php

namespace frontend\models\requests;

use yii\base\Model;
use yii\db\ActiveRecord;

class UsersSearchForm extends Model
{
    public $categories;
    public $hasFeedback;
    public $online;
    public $free;
    public $searchName;
    public $bookmarked;

    public function rules()
    {
        return [
            [['categories', 'hasFeedback', 'online', 'free', 'bookmarked', 'searchName'], 'safe'],
            [['searchName'], 'string', 'max' => 100],
            [['hasFeedback', 'online', 'free', 'bookmarked'], 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'categories' => 'Категории',
            'hasFeedback' => 'Без откликов',
            'online' => 'Удаленная работа',
            'searchName' => 'Поиск по имени',
            'free' => 'Свободен',
            'bookmarked' => 'В закладках'
        ];
    }
}
