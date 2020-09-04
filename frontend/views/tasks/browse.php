<?php

/* @var $this yii\web\View */

/* @var $tasks frontend\models\Task[] */

/* @var $request frontend\models\requests\TasksSearchForm */

use yii\helpers\Html;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

$this->title = 'Task Force';

$searchFormConfig = [
    'method' => 'get',
    'id' => 'filterForm',
    'action' => 'tasks',
    'options' => [
        'class' => 'search-task__form',
    ],
];

?>
<section class="new-task">
    <div class="new-task__wrapper">
        <h1>Новые задания</h1>
        <?php
        foreach ($tasks as $task): ?>
            <div class="new-task__card">
                <div class="new-task__title">
                    <a href="#" class="link-regular"><h2><?= Html::encode($task->title) ?></h2></a>
                    <a class="new-task__type link-regular" href="#"><p><?= $task->category->name ?></p></a>
                </div>
                <div class="new-task__icon new-task__icon--<?= $task->category->icon ?>"></div>
                <p class="new-task_description">
                    <?= Html::encode($task->description) ?>
                </p>
                <b class="new-task__price new-task__price--translation"><?= $task->budget ?><b> ₽</b></b>
                <p class="new-task__place"><?= $task->city->name ?>, <?= Html::encode($task->address) ?></p>
                <span class="new-task__time"><?= Yii::$app->formatter->asRelativeTime($task->created_at) ?></span>
            </div>
        <?php
        endforeach; ?>
    </div>
    <div class="new-task__pagination">
        <ul class="new-task__pagination-list">
            <li class="pagination__item"><a href="#"></a></li>
            <li class="pagination__item pagination__item--current">
                <a>1</a></li>
            <li class="pagination__item"><a href="#">2</a></li>
            <li class="pagination__item"><a href="#">3</a></li>
            <li class="pagination__item"><a href="#"></a></li>
        </ul>
    </div>
</section>
<section class="search-task">
    <div class="search-task__wrapper">

        <?php $form = ActiveForm::begin($searchFormConfig); ?>
            <fieldset class="search-task__categories">
                <legend>Категории</legend>
                <?= \yii\helpers\BaseHtml::activeCheckboxList($request, 'categories', \yii\helpers\ArrayHelper::map($categories, 'id', 'name'), [
                        ['tag' => false, 'inputOptions' => ['class' => 'visually-hidden checkbox__input']]
                ]) ?>
            </fieldset>
            <fieldset class="search-task__categories">
                <legend>Дополнительно</legend>
                <?= \yii\helpers\BaseHtml::activeCheckbox($request, 'withoutApplications', ['tag' => false, 'inputOptions' => ['class' => 'visually-hidden checkbox__input']]) ?>
                <?= \yii\helpers\BaseHtml::activeCheckbox($request, 'remote', ['tag' => false, 'inputOptions' => ['class' => 'visually-hidden checkbox__input']]) ?>
            </fieldset>
            <label class="search-task__name">Период</label>
            <?= \yii\helpers\BaseHtml::activeDropDownList($request, 'period', $request->getPeriods(), ['class' => 'input multiple-select']) ?>
            <label class="search-task__name" for="9">Поиск по названию</label>
            <?= \yii\helpers\BaseHtml::activeInput('search', $request, 'searchName', ['class' => 'input-middle input']) ?>
            <button class="button" type="submit">Искать</button>
        <?php ActiveForm::end(); ?>
    </div>
</section>
