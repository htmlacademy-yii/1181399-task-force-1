<?php

/* @var $this yii\web\View */

/* @var $user frontend\models\User */

/* @var $taskStateMachine Htmlacademy\Models\TaskStateMachine */
/* @var $model AccountForm */

use frontend\models\requests\AccountForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Task Force';

?>

<section class="account__redaction-wrapper">
    <h1>Редактирование настроек профиля</h1>
    <?php $form = ActiveForm::begin(['method' => 'POST', 'action' => Url::to(['account/index']), 'options' => ['enctype' => 'multipart/form-data']]) ?>
        <div class="account__redaction-section">
            <h3 class="div-line">Настройки аккаунта</h3>
            <div class="account__redaction-section-wrapper">
                <div class="account__redaction-avatar">
                    <?php if ($user->avatar_url): ?>
                    <img src="<?= $user->avatar_url ?>" width="156" height="156">
                    <?php endif; ?>
                    <?= $form->field($model, 'avatar')->fileInput(['id' => 'upload-avatar']) ?>
                    <label for="upload-avatar" class="link-regular">Сменить аватар</label>
                </div>
                <div class="account__redaction">
                    <div class="account__input account__input--name">
                        <label for="200">Ваше имя</label>
                        <input class="input textarea" id="200" name="" placeholder="<?= $user->name ?>" disabled>
                    </div>
                    <div class="account__input account__input--email">
                        <label for="201">email</label>
                        <?= $form->field($model, 'email', ['template' => "{input}\n{error}"])->input('text', ['value' => $user->email, 'class' => 'input textarea']) ?>
                    </div>
                    <div class="account__input account__input--name">
                        <label for="202">Город</label>
                        <?= $form->field($model, 'city_id', ['inputOptions' => ['class' => 'multiple-select input multiple-select-big'], 'template' => '{input}{error}'])
                            ->dropDownList(ArrayHelper::map($cities, 'id', 'name')) ?>
                    </div>
                    <div class="account__input account__input--date">
                        <label for="203">День рождения</label>
                        <?= $form->field($model, 'birthday', ['template' => "{input}\n{error}"])->input('date', ['value' => $user->birthday, 'class' => 'input-middle input input-date']) ?>
                    </div>
                    <div class="account__input account__input--info">
                        <label for="204">Информация о себе</label>
                        <?= $form->field($model, 'description', ['template' => "{input}\n{error}"])->textarea(['value' => $user->description, 'class' => 'input textarea', 'rows' => 7, 'cols' => 112]) ?>
                    </div>
                </div>
            </div>
            <h3 class="div-line">Выберите свои специализации</h3>
            <div class="account__redaction-section-wrapper">
                <div class="search-task__categories account_checkbox--bottom">
                    <?= \yii\helpers\BaseHtml::activeCheckboxList($model, 'specializations', \yii\helpers\ArrayHelper::map($categories, 'id', 'name'), [
                        ['tag' => false, 'inputOptions' => ['class' => 'visually-hidden checkbox__input']]
                    ]) ?>
                </div>
            </div>
            <h3 class="div-line">Безопасность</h3>
            <div class="account__redaction-section-wrapper account__redaction">
                <div class="account__input">
                    <label for="211">Новый пароль</label>
                    <?= $form->field($model, 'password', ['template' => "{input}\n{error}"])->input('password', ['class' => 'input textarea']) ?>
                </div>
                <div class="account__input">
                    <label for="212">Повтор пароля</label>
                    <?= $form->field($model, 'password_confirmation', ['template' => "{input}\n{error}"])->input('password', ['class' => 'input textarea']) ?>
                </div>
            </div>

            <h3 class="div-line">Фото работ</h3>

            <div class="account__redaction-section-wrapper account__redaction">
                <span class="dropzone">Выбрать фотографии</span>
            </div>

            <h3 class="div-line">Контакты</h3>
            <div class="account__redaction-section-wrapper account__redaction">
                <div class="account__input">
                    <label for="213">Телефон</label>
                    <?= $form->field($model, 'phone', ['template' => "{input}\n{error}"])->input('text', ['value' => $user->phone, 'class' => 'input textarea']) ?>
                </div>
                <div class="account__input">
                    <label for="214">Skype</label>
                    <?= $form->field($model, 'skype', ['template' => "{input}\n{error}"])->input('text', ['value' => $user->skype, 'class' => 'input textarea']) ?>
                </div>
                <div class="account__input">
                    <label for="215">Telegram</label>
                    <?= $form->field($model, 'telegram', ['template' => "{input}\n{error}"])->input('text', ['value' => $user->telegram, 'class' => 'input textarea']) ?>
                </div>
            </div>
            <h3 class="div-line">Настройки сайта</h3>
            <h4>Уведомления</h4>
            <div class="account__redaction-section-wrapper account_section--bottom">
                <div class="search-task__categories account_checkbox--bottom">
                    <?= $form->field($model, 'notifications_actions', ['template' => "{input}\n{error}"])->checkbox(['label' => 'Новое сообщение', 'value' => 1, 'uncheckValue' => 0, 'checked' => $user->notification_feedback]) ?>
                    <?= $form->field($model, 'notifications_message', ['template' => "{input}\n{error}"])->checkbox(['label' => 'Действия по заданию', 'value' => 1, 'uncheckValue' => 0, 'checked' => $user->notification_feedback]) ?>
                    <?= $form->field($model, 'notifications_feedback', ['template' => "{input}\n{error}"])->checkbox(['label' => 'Новый отзыв', 'value' => 1, 'uncheckValue' => 0, 'checked' => $user->notification_feedback]) ?>
                </div>
                <div class="search-task__categories account_checkbox account_checkbox--secrecy">
                    <?= $form->field($model, 'hide_contacts', ['template' => "{input}\n{error}"])->checkbox(['label' => 'Показывать мои контакты только заказчику', 'value' => 1, 'uncheckValue' => 0,  'checked' => $user->public_contacts]) ?>
                    <?= $form->field($model, 'hide_profile', ['template' => "{input}\n{error}"])->checkbox(['label' => 'Не показывать мой профиль','value' => 1, 'uncheckValue' => 0,  'checked' => $user->public_profile]) ?>

                </div>
            </div>
        </div>
        <button class="button" type="submit">Сохранить изменения</button>
    <?php ActiveForm::end(); ?>
</section>
<script src="js/dropzone.js"></script>
<script>
    Dropzone.autoDiscover = false;

    var dropzone = new Dropzone(".dropzone", {url: window.location.href, maxFiles: 6, uploadMultiple: true,
        acceptedFiles: 'image/*', previewTemplate: '<a href="#"><img data-dz-thumbnail alt="Фото работы"></a>'});
</script>
