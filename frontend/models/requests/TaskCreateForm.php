<?php

namespace frontend\models\requests;

use frontend\models\Attachment;
use frontend\models\Category;
use frontend\models\City;
use frontend\models\Task;
use frontend\models\User;
use yii\base\Model;

class TaskCreateForm extends Model
{
    public $title;
    public $description;
    public $category;
    public $budget;
    public $until;
    public $files = [];

    public function rules()
    {
        return [
            [['title', 'description', 'category', 'budget', 'until'], 'safe'],
            [['title', 'description', 'category', 'budget', 'until'], 'required'],
            [['title', 'description'], 'string'],
            [['category'], 'exist', 'targetClass' => Category::class, 'targetAttribute' => 'id'],
            [['budget'], 'number'],
            [['until'], 'date', 'format' => 'dd.MM.yyyy'],
            [['files'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 0],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Название',
            'description' => 'Описание задания',
            'category' => 'Категория',
            'budget' => 'Бюджет',
            'until' => 'Дата окончания',
            'files' => 'Файлы'
        ];
    }

    public function upload(Task $task)
    {
        if ($this->validate()) {
            $ids = [];
            foreach ($this->files as $file) {
                $name = 'uploads/' . $file->baseName . '.' . $file->extension;
                $file->saveAs($name);
                $uploadedFile = new Attachment();
                $uploadedFile->name = $file->baseName . '.' . $file->extension;
                $uploadedFile->url = $name;
                $uploadedFile->save();

                $uploadedFile->link('attachmentTasks', $task);
            }
            return true;
        } else {
            return false;
        }
    }

    public function saveTask()
    {
        if (!$this->validate()) {
            return false;
        }

        $task = new Task();
        $task->budget = $this->budget;
        $task->title = $this->title;
        $task->description = $this->description;
        $task->until = $this->until;
        $task->author_id = \Yii::$app->user->getId();

        $task->save();

        $this->upload($task);

        return true;
    }
}
