<?php

/* @var $this yii\web\View */

/* @var $task frontend\models\Task */
/* @var $author frontend\models\User */
/* @var $applications frontend\models\Application[] */

use yii\helpers\Html;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

$this->title = 'Task Force';

?>
<section class="content-view">
    <div class="content-view__card">
        <div class="content-view__card-wrapper">
            <div class="content-view__header">
                <div class="content-view__headline">
                    <h1><?= Html::encode($task->title) ?></h1>
                    <span>Размещено в категории
                                    <a href="#" class="link-regular"><?= $task->category->name ?></a>
                                    <?= Yii::$app->formatter->asRelativeTime($task->created_at) ?></span>
                </div>
                <b class="new-task__price new-task__price--clean content-view-price"><?= Html::encode($task->budget) ?><b> ₽</b></b>
                <div class="new-task__icon new-task__icon--<?= $task->category->icon ?> content-view-icon"></div>
            </div>
            <div class="content-view__description">
                <h3 class="content-view__h3">Общее описание</h3>
                <p>
                    <?= Html::encode($task->description) ?>
                </p>
            </div>
            <?php if (count($task->attachments) > 0): ?>
            <div class="content-view__attach">
                <h3 class="content-view__h3">Вложения</h3>
                <?php foreach($task->attachments as $attachment): ?>
                <a href="#"><?= Html::encode($attachment->name) ?></a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <div class="content-view__location">
                <h3 class="content-view__h3">Расположение</h3>
                <div class="content-view__location-wrapper">
                    <div class="content-view__map">
                        <a href="#"><img src="/img/map.jpg" width="361" height="292"
                                         alt="<?= Html::encode($task->address) ?>"></a>
                    </div>
                    <div class="content-view__address">
                        <span class="address__town"><?= $task->city->name ?? '' ?></span><br>
                        <span><?= Html::encode($task->address) ?></span>
                        <p><?= Html::encode($task->address_comment) ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-view__action-buttons">
            <button class=" button button__big-color response-button open-modal"
                    type="button" data-for="response-form">Откликнуться</button>
            <button class="button button__big-color refusal-button open-modal"
                    type="button" data-for="refuse-form">Отказаться</button>
            <button class="button button__big-color request-button open-modal"
                    type="button" data-for="complete-form">Завершить</button>
        </div>
    </div>
    <div class="content-view__feedback">
        <h2>Отклики <span>(<?= count($applications) ?>)</span></h2>
        <div class="content-view__feedback-wrapper">
            <?php foreach($applications as $application): ?>
            <div class="content-view__feedback-card">
                <div class="feedback-card__top">
                    <a href="#"><img src="/img/man-glasses.jpg" width="55" height="55"></a>
                    <div class="feedback-card__top--name">
                        <p><a href="#" class="link-regular"><?= Html::encode($application->user->name) ?></a></p>
                        <span></span><span></span><span></span><span></span><span class="star-disabled"></span>
                        <b><?= $application->user->getRatingSum() ?></b>
                    </div>
                    <span class="new-task__time"><?= Yii::$app->formatter->asRelativeTime($application->created_at) ?></span>
                </div>
                <div class="feedback-card__content">
                    <p>
                        <?= Html::encode($application->comment) ?>
                    </p>
                    <span><?= Html::encode($application->budget) ?> ₽</span>
                </div>
                <div class="feedback-card__actions">
                    <a class="button__small-color request-button button"
                       type="button">Подтвердить</a>
                    <a class="button__small-color refusal-button button"
                       type="button">Отказать</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="connect-desk">
    <div class="connect-desk__profile-mini">
        <div class="profile-mini__wrapper">
            <h3>Заказчик</h3>
            <div class="profile-mini__top">
                <img src="/img/man-brune.jpg" width="62" height="62" alt="Аватар заказчика">
                <div class="profile-mini__name five-stars__rate">
                    <p><?= Html::encode($author->name) ?></p>
                </div>
            </div>
            <p class="info-customer"><span><?= $author->getTasksCount() ?> заданий</span><span class="last-"><?= Yii::$app->formatter->asRelativeTime($author->last_visit) ?></span></p>
            <a href="#" class="link-regular">Смотреть профиль</a>
        </div>
    </div>
    <div id="chat-container">
        <!--                    добавьте сюда атрибут task с указанием в нем id текущего задания-->
        <chat class="connect-desk__chat"></chat>
    </div>
</section>
