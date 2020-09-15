<?php

/* @var $this yii\web\View */

/* @var $users frontend\models\User[] */
/* @var $request UsersSearchForm */

/* @var $categories frontend\models\Category[] */

use frontend\models\requests\UsersSearchForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$formConfig = [
    'method' => 'post',
    'action' => 'registration',
    'options' => [
        'class' => 'registration__user-form form-create',
    ],
];

?>

<section class="registration__user">
    <h1>Регистрация аккаунта</h1>
    <div class="registration-wrapper">
        <?php
        $form = ActiveForm::begin($formConfig); ?>
        <label>Ваш email</label>
        <?= \yii\helpers\BaseHtml::activeInput('text', $request, 'email', ['class' => 'input textarea']) ?>
        <label>Ваше имя</label>
        <?= \yii\helpers\BaseHtml::activeInput('text', $request, 'name', ['class' => 'input textarea']) ?>
        <span>Введите ваше имя и фамилию</span>
        <label>Город проживания</label>
        <?= \yii\helpers\BaseHtml::activeDropDownList(
            $request,
            'city',
            \yii\helpers\ArrayHelper::map($cities, 'id', 'name'),
            ['class' => 'multiple-select input town-select registration-town']
        ) ?>
        <label>Пароль</label>
        <?= Html::activeInput('password', $request, 'password', ['class' => 'input textarea']) ?>
        <span>Длина пароля от 8 символов</span>
        <?= Html::button('Создать аккаунт', ['class' => 'button button__registration', 'type' => 'submit']) ?>
        <?php
        $form = ActiveForm::end(); ?>
    </div>
</section>
