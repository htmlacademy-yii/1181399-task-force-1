<?php

/* @var $this yii\web\View */

/* @var $task frontend\models\Task */

/* @var $model \frontend\models\requests\TaskCreateForm */

use yii\helpers\Html;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

$this->title = 'Task Force';

?>
<section class="create__task">
    <h1>Публикация нового задания</h1>
    <div class="create__task-main">
        <?php $form = ActiveForm::begin(
            [
                'action' => '/tasks/create',
                'method' => 'post',
                'options' => ['enctype' => 'multipart/form-data', 'class' => 'create__task-form form-create'],
            ]
        ) ?>
        <form class="create__task-form form-create" action="/" enctype="multipart/form-data" id="task-form">
            <label for="10">Мне нужно</label>
            <?= $form->field($model, 'title', ['inputOptions' => ['class' => 'input textarea'], 'template' => '{input}{error}'])->textarea(['rows' => 1]) ?>
            <span>Кратко опишите суть работы</span>
            <label for="11">Подробности задания</label>
            <?= $form->field($model, 'description', ['inputOptions' => ['class' => 'input textarea'], 'template' => '{input}{error}'])->textarea(['rows' => 5]) ?>
            <span>Укажите все пожелания и детали, чтобы исполнителям было проще соориентироваться</span>
            <label for="12">Категория</label>
            <?= $form->field($model, 'category', ['inputOptions' => ['class' => 'multiple-select input multiple-select-big'], 'template' => '{input}{error}'])
                ->dropDownList(\yii\helpers\ArrayHelper::map($categories, 'id', 'name')) ?>
            <span>Выберите категорию</span>
            <label>Файлы</label>
            <span>Загрузите файлы, которые помогут исполнителю лучше выполнить или оценить работу</span>
            <div class="create__file">
                <span>Добавить новый файл</span>
                <?= $form->field($model, 'files[]', ['inputOptions' => ['class' => 'dropzone']])->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>
                <!--                          <input type="file" name="files[]" class="dropzone">-->
            </div>
            <label for="13">Локация</label>
            <input class="input-navigation input-middle input" id="13" type="search" name="q" placeholder="Санкт-Петербург, Калининский район">
            <span>Укажите адрес исполнения, если задание требует присутствия</span>
            <div class="create__price-time">
                <div class="create__price-time--wrapper">
                    <label for="14">Бюджет</label>
                    <?= $form->field($model, 'budget', ['inputOptions' => ['class' => 'multiple-select input multiple-select-big'], 'template' => '{input}{error}'])
                        ->textarea(['rows' => 1])
                    ?>
                    <span>Не заполняйте для оценки исполнителем</span>
                </div>
                <div class="create__price-time--wrapper">
                    <label for="15">Срок исполнения</label>
                    <?= $form->field($model, 'until', ['inputOptions' => ['class' => 'input-middle input input-date', 'placeholder' => 'yyyy-mm-dd'], 'template' => '{input}{error}'])
                        ->input('text')
                    ?>
                    <span>Укажите крайний срок исполнения</span>
                </div>
            </div>
            <button class="button" type="submit">Опубликовать</button>
        <?php ActiveForm::end(); ?>
        <div class="create__warnings">
            <div class="warning-item warning-item--advice">
                <h2>Правила хорошего описания</h2>
                <h3>Подробности</h3>
                <p>Друзья, не используйте случайный<br>
                    контент – ни наш, ни чей-либо еще. Заполняйте свои
                    макеты, вайрфреймы, мокапы и прототипы реальным
                    содержимым.</p>
                <h3>Файлы</h3>
                <p>Если загружаете фотографии объекта, то убедитесь,
                    что всё в фокусе, а фото показывает объект со всех
                    ракурсов.</p>
            </div>
            <?php if ($model->hasErrors()): ?>
            <div class="warning-item warning-item--error">
                <h2>Ошибки заполнения формы</h2>
                <?php foreach ($model->getErrors() as $field => $error): ?>
                    <h3><?= $field ?></h3>
                    <p><?php foreach($error as $err): ?>
                        <?= $err ?><br>
                        <?php endforeach; ?></p>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <button form="task-form" class="button" type="submit">Опубликовать</button>
</section>
