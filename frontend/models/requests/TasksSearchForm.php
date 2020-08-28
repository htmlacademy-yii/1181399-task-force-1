<?php

namespace frontend\models\requests;

use yii\base\Model;
use yii\db\ActiveRecord;

class TasksSearchForm extends Model
{
    public $categories;
    public $withoutApplications;
    public $remote;
    public $period;
    public $searchName;

    public function rules()
    {
        return [
            [['categories', 'withoutApplications', 'remote', 'period', 'searchName'], 'safe'],
            [['searchName'], 'string', 'max' => 100],
            [['withoutApplications', 'remote'], 'boolean'],
            [['period'], 'in', 'range' => ['day', 'week', 'month']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'categories' => 'Категории',
            'withoutApplications' => 'Без откликов',
            'remote' => 'Удаленная работа',
            'period' => 'Период',
            'searchName' => 'Поиск по имени',
        ];
    }
}
