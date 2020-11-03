<?php

/* @var $this yii\web\View */

/* @var $task frontend\models\Task */

/* @var $taskStateMachine Htmlacademy\Models\TaskStateMachine */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

$this->title = 'Task Force';

\frontend\assets\TasksAsset::register($this);

$availableActions = $taskStateMachine->getActions(Yii::$app->user->getId());

?>
<section class="content-view">
    <div class="content-view__card">
        <div class="content-view__card-wrapper">
            <div class="content-view__header">
                <div class="content-view__headline">
                    <h1><?= Html::encode($task->title) ?></h1>
                    <span>Размещено в категории
                                    <a href="<?= Url::toRoute(['tasks/index', 'categories[]' => $task->category->id]) ?>" class="link-regular"><?= $task->category->name ?></a>
                                    <?= Yii::$app->formatter->asRelativeTime($task->created_at) ?></span>
                </div>
                <b class="new-task__price new-task__price--clean content-view-price"><?= Html::encode($task->budget) ?>
                    <b> ₽</b></b>
                <div class="new-task__icon new-task__icon--<?= $task->category->icon ?> content-view-icon"></div>
            </div>
            <div class="content-view__description">
                <h3 class="content-view__h3">Общее описание</h3>
                <p>
                    <?= Html::encode($task->description) ?>
                </p>
            </div>
            <?php
            if (count($task->attachments) > 0): ?>
                <div class="content-view__attach">
                    <h3 class="content-view__h3">Вложения</h3>
                    <?php
                    foreach ($task->attachments as $attachment): ?>
                        <a href="/<?= $attachment->url ?>" download="<?= $attachment->name ?>"><?= Html::encode(
                                $attachment->name
                            ) ?></a>
                    <?php
                    endforeach; ?>
                </div>
            <?php
            endif; ?>
            <div class="content-view__location">
                <h3 class="content-view__h3">Расположение</h3>
                <div class="content-view__location-wrapper">
                    <div class="content-view__map">
                        <div id="map" style="width: 361px; height: 292px"></div>
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
            <?php
            if (!Yii::$app->user->getIdentity()->isAuthor() && !Yii::$app->user->getIdentity()->applied($task->id) && !Yii::$app->user->getId() === $task->author_id): ?>
                <button class=" button button__big-color response-button open-modal"
                        type="button" data-for="response-form">Откликнуться
                </button>
            <?php
            endif; ?>
            <?php
            if (in_array(\Htmlacademy\Enums\Actions::DECLINE, $availableActions)): ?>
                <button class="button button__big-color refusal-button open-modal"
                        type="button" data-for="refuse-form">Отказаться
                </button>
            <?php
            endif; ?>
            <?php
            if (in_array(\Htmlacademy\Enums\Actions::DONE, $availableActions)): ?>
                <button class="button button__big-color request-button open-modal"
                        type="button" data-for="complete-form">Завершить
                </button>
            <?php
            endif; ?>
        </div>
    </div>
    <?php
    if (count($task->applications) > 0): ?>
        <div class="content-view__feedback">
            <h2>Отклики <span>(<?= count($task->applications) ?>)</span></h2>
            <div class="content-view__feedback-wrapper">
                <?php
                foreach ($task->applications as $application): ?>
                    <?php
                    if (Yii::$app->user->getId() === $task->author_id ||
                        Yii::$app->user->getId() === $application->user_id): ?>
                        <div class="content-view__feedback-card">
                            <div class="feedback-card__top">
                                <a href="#"><img src="/<?= $application->user->avatar_url ?? 'img/man-glasses.jpg' ?>" width="55" height="55"></a>
                                <div class="feedback-card__top--name">
                                    <p><a href="#" class="link-regular"><?= Html::encode(
                                                $application->user->name
                                            ) ?></a></p>
                                    <span></span><span></span><span></span><span></span><span
                                            class="star-disabled"></span>
                                    <b><?= $application->user->getRatingSum() ?></b>
                                </div>
                                <span class="new-task__time"><?= Yii::$app->formatter->asRelativeTime(
                                        $application->created_at
                                    ) ?></span>
                            </div>
                            <div class="feedback-card__content">
                                <p>
                                    <?= Html::encode($application->comment) ?>
                                </p>
                                <span><?= $application->budget ?> ₽</span>
                            </div>
                            <div class="feedback-card__actions">
                                <?php
                                if ($application->status === 'new' && Yii::$app->user->getId() === $task->author_id): ?>
                                    <a class="button__small-color request-button button" href="<?= Url::toRoute(
                                        ['applications/accept', 'applicationId' => $application->id]
                                    ) ?>"
                                       type="button">Подтвердить</a>
                                    <a class="button__small-color refusal-button button" href="<?= Url::toRoute(
                                        ['applications/reject', 'applicationId' => $application->id]
                                    ) ?>"
                                       type="button">Отказать</a>
                                <?php
                                endif; ?>
                            </div>
                        </div>
                    <?php
                    endif; ?>
                <?php
                endforeach; ?>
            </div>
        </div>
    <?php
    endif; ?>
</section>
<section class="connect-desk">
    <div class="connect-desk__profile-mini">
        <div class="profile-mini__wrapper">
            <h3>Заказчик</h3>
            <div class="profile-mini__top">
                <img src="/<?= $task->author->avatar_url ?>" width="62" height="62" alt="Аватар заказчика">
                <div class="profile-mini__name five-stars__rate">
                    <p><?= Html::encode($task->author->name) ?></p>
                </div>
            </div>
            <p class="info-customer"><span><?= $task->author->getTasksCount() ?> заданий</span><span
                        class="last-"><?= Yii::$app->formatter->asRelativeTime($task->author->last_visit) ?></span></p>
            <a href="<?= Url::toRoute(['users/view', 'id' => $task->author_id]) ?>" class="link-regular">Смотреть профиль</a>
        </div>
    </div>
    <div id="chat-container">
        <chat class="connect-desk__chat" task="<?= $task->id ?>"></chat>
    </div>
</section>

<section class="modal response-form form-modal" id="response-form">
    <h2>Отклик на задание</h2>
    <?php
    $form = ActiveForm::begin(
        [
            'method' => 'POST',
            'action' => Url::to('/applications/create'),
        ]
    ); ?>
    <p>
        <label class="form-modal-description" for="response-payment">Ваша цена</label>
        <?= $form->field(
            $applicationModel,
            'budget',
            [
                'inputOptions' => ['class' => 'response-form-payment input input-middle input-money'],
                'template' => "{input}\n{error}"
            ]
        )->input('text') ?>
    </p>
    <p>
        <label class="form-modal-description" for="response-comment">Комментарий</label>
        <?= $form->field(
            $applicationModel,
            'comment',
            [
                'inputOptions' => ['class' => 'response-form-payment input input-middle input-money'],
                'template' => "{input}\n{error}"
            ]
        )->textarea() ?>
    </p>
    <?= $form->field(
        $applicationModel,
        'task_id',
        [
            'inputOptions' => ['class' => 'response-form-payment input input-middle input-money'],
            'template' => '{input}'
        ]
    )->input('hidden', ['value' => $task->id]) ?>
    <?= $form->field(
        $applicationModel,
        'user_id',
        ['inputOptions' => ['class' => 'input textarea'], 'template' => '{input}']
    )->input('hidden', ['value' => Yii::$app->user->getId()]) ?>
    <button class="button modal-button" type="submit">Отправить</button>

    <?php
    ActiveForm::end(); ?>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>
<section class="modal completion-form form-modal" id="complete-form">
    <h2>Завершение задания</h2>
    <p class="form-modal-description">Задание выполнено?</p>
    <?php
    $form = ActiveForm::begin(
        ['action' => Url::toRoute('applications/done'), 'method' => 'POST']
    ); // здесь нужна помощь по созданию радиокнопок.?>
    <?= Html::activeRadio(
        $doneModel,
        'done',
        [
            'class' => "visually-hidden completion-input completion-input--yes",
            'id' => 'completion-radio--yes',
            'value' => 'done',
            'label' => false,
            'type' => 'radio',
            'uncheck' => false
        ]
    ) ?>
    <label class="completion-label completion-label--yes" for="completion-radio--yes">Да</label>
    <?= Html::activeRadio(
        $doneModel,
        'done',
        [
            'class' => "visually-hidden completion-input completion-input--difficult",
            'id' => 'completion-radio--yet',
            'value' => 'difficulties',
            'label' => false,
            'type' => 'radio',
            'uncheck' => false
        ]
    ) ?>
    <label class="completion-label completion-label--difficult" for="completion-radio--yet">Возникли проблемы</label>
    <p>
        <label class="form-modal-description" for="completion-comment">Комментарий</label>
        <?= $form->field($doneModel, 'comment', ['template' => '{input}'])->textarea(
            ['class' => 'input textarea', 'id' => 'completion-comment']
        ) ?>
    </p>
    <p class="form-modal-description">
        Оценка
    <div class="feedback-card__top--name completion-form-star">
        <span class="star-disabled"></span>
        <span class="star-disabled"></span>
        <span class="star-disabled"></span>
        <span class="star-disabled"></span>
        <span class="star-disabled"></span>
    </div>
    </p>
    <?= $form->field(
        $doneModel,
        'rating',
        ['inputOptions' => ['id' => 'rating'], 'template' => '{input}']
    )->hiddenInput(); ?>
    <?= $form->field(
        $doneModel,
        'taskId',
        ['inputOptions' => ['id' => 'rating'], 'template' => '{input}']
    )->hiddenInput(['value' => $task->id]); ?>
    <button class="button modal-button" type="submit">Отправить</button>
    <?php
    ActiveForm::end() ?>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>
<section class="modal form-modal refusal-form" id="refuse-form">
    <h2>Отказ от задания</h2>
    <p>
        Вы собираетесь отказаться от выполнения задания.
        Это действие приведёт к снижению вашего рейтинга.
        Вы уверены?
    </p>
    <button class="button__form-modal button" id="close-modal"
            type="button">Отмена
    </button>
    <?php
    ActiveForm::begin(['action' => Url::toRoute(['applications/fail', 'taskId' => $task->id]), 'method' => 'POST']); ?>
    <button class="button__form-modal refusal-button button"
            type="submit">Отказаться
    </button>
    <?php
    ActiveForm::end(); ?>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>
<div class="overlay"></div>
<script>
    window.W = <?= $task->map_w ?? '55.76' ?>;
    window.H = <?= $task->map_h ?? '36.76' ?>;
</script>
