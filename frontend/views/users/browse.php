<?php

/* @var $this yii\web\View */
/* @var $users frontend\models\User[] */
/* @var $request UsersSearchForm */
/* @var $categories frontend\models\Category[] */

use frontend\models\requests\UsersSearchForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Task Force';

$searchFormConfig = [
    'method' => 'get',
    'id' => 'filterForm',
    'action' => 'users',
    'options' => [
        'class' => 'search-task__form',
    ],
];
?>
<section class="user__search">
    <?php foreach ($users as $user): ?>
    <div class="content-view__feedback-card user__search-wrapper">
        <div class="feedback-card__top">
            <div class="user__search-icon">
                <a href="#"><img src="/img/man-glasses.jpg" width="65" height="65"></a>
                <span><?= $user->tasks_count ?> заданий</span>
                <span><?= $user->feedback_count ?> отзывов</span>
            </div>
            <div class="feedback-card__top--name user__search-card">
                <p class="link-name"><a href="<?= \yii\helpers\BaseUrl::to(['users/view/', 'id' => $user->id]) ?>" class="link-regular"><?= Html::encode($user->name) ?></a></p>
                <span></span><span></span><span></span><span></span><span class="star-disabled"></span>
                <b><?= $user->rating ?? 0 ?></b>
                <p class="user__search-content">
                    <?= Html::encode($user->description) ?>
                </p>
            </div>
            <span class="new-task__time"><?= $user->isOnline() ? 'Сейчас на сайте' : 'Был на сайте ' . Yii::$app->formatter->asRelativeTime($user->last_visit) ?></span>
        </div>
        <div class="link-specialization user__search-link--bottom">
            <?php foreach ($user->categories as $category): ?>
                <a href="<?= Url::toRoute(['tasks/index', 'categories[]' => $category->id]) ?>" class="link-regular"><?= $category->name ?></a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>

    <div>
        <?= \yii\widgets\LinkPager::widget(
            [
                'pagination' => $pages,
                'pageCssClass' => 'pagination__item',
                'prevPageCssClass' => 'pagination__item',
                'prevPageLabel' => '',
                'nextPageLabel' => '',
                'nextPageCssClass' => 'pagination__item',
                'options' => ['class' => 'new-task__pagination-list'],
                'activePageCssClass' => 'pagination__item--current',
            ]
        ) ?>
    </div>
</section>

<section  class="search-task">
    <div class="search-task__wrapper">
        <?php $form = ActiveForm::begin($searchFormConfig); ?>
            <fieldset class="search-task__categories">
                <legend>Сортировка</legend>

                <?= $form->field($request, 'sort', [ 'template' => '{input}{error}'])->dropDownList([
                     'rating' => 'Рейтинг',
                     'popularity' => 'Популярность',
                     'tasks' => 'Кол-во заказов',
                 ], ['class' => 'multiple-select input multiple-select-big']);?>
            </fieldset>
            <fieldset class="search-task__categories">
                <legend>Категории</legend>
                <?= \yii\helpers\BaseHtml::activeCheckboxList($request, 'categories', \yii\helpers\ArrayHelper::map($categories, 'id', 'name'), [
                    ['tag' => false, 'inputOptions' => ['class' => 'visually-hidden checkbox__input']]
                ]) ?>
            </fieldset>
            <fieldset class="search-task__categories">
                <legend>Дополнительно</legend>
                <?= \yii\helpers\BaseHtml::activeCheckbox($request, 'free', ['tag' => false, 'inputOptions' => ['class' => 'visually-hidden checkbox__input']]) ?>
                <?= \yii\helpers\BaseHtml::activeCheckbox($request, 'online', ['tag' => false, 'inputOptions' => ['class' => 'visually-hidden checkbox__input']]) ?>
                <?= \yii\helpers\BaseHtml::activeCheckbox($request, 'hasFeedback', ['tag' => false, 'inputOptions' => ['class' => 'visually-hidden checkbox__input']]) ?>
                <?= \yii\helpers\BaseHtml::activeCheckbox($request, 'bookmarked', ['tag' => false, 'inputOptions' => ['class' => 'visually-hidden checkbox__input']]) ?>
            </fieldset>
            <label class="search-task__name" for="110">Поиск по имени</label>
            <?= \yii\helpers\BaseHtml::activeInput('search', $request, 'searchName', ['class' => 'input-middle input']) ?>
            <button class="button" type="submit">Искать</button>
        <?php ActiveForm::end(); ?>
    </div>
</section>
