<?php

/* @var $this yii\web\View */

/* @var $model frontend\models\User */

/* @var $taskStateMachine Htmlacademy\Models\TaskStateMachine */
/* @var $model AccountForm */

use frontend\models\requests\AccountForm;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

$this->title = 'Task Force';

?>

<section class="account__redaction-wrapper">
    <h1>Редактирование настроек профиля</h1>
    <?php $form = ActiveForm::begin(['method' => 'POST', 'options' => ['enctype' => 'multipart/form-data']]) ?>
        <div class="account__redaction-section">
            <h3 class="div-line">Настройки аккаунта</h3>
            <div class="account__redaction-section-wrapper">
                <div class="account__redaction-avatar">
                    <?php if ($model->avatar_url): ?>
                    <img src="<?= $model->avatar_url ?>" width="156" height="156">
                    <?php endif; ?>
                    <?= $form->field($model, 'avatar')->fileInput(['id' => 'upload-avatar']) ?>
                    <label for="upload-avatar" class="link-regular">Сменить аватар</label>
                </div>
                <div class="account__redaction">
                    <div class="account__input account__input--name">
                        <label for="200">Ваше имя</label>
                        <input class="input textarea" id="200" name="" placeholder="<?= $model->name ?>" disabled>
                    </div>
                    <div class="account__input account__input--email">
                        <label for="201">email</label>
                        <?= $form->field($model, 'email', ['template' => "{input}\n{error}"])->input('text', ['class' => 'input textarea']) ?>
                    </div>
                    <div class="account__input account__input--name">
                        <label for="202">Город</label>
                        <?= $form->field($model, 'city_id', ['inputOptions' => ['class' => 'multiple-select input multiple-select-big'], 'template' => '{input}{error}'])
                            ->dropDownList(ArrayHelper::map($cities, 'id', 'name')) ?>
                    </div>
                    <div class="account__input account__input--date">
                        <label for="203">День рождения</label>
                        <?= $form->field($model, 'birthday', ['template' => "{input}\n{error}"])->input('date', ['class' => 'input-middle input input-date']) ?>
                    </div>
                    <div class="account__input account__input--info">
                        <label for="204">Информация о себе</label>
                        <?= $form->field($model, 'description', ['template' => "{input}\n{error}"])->textarea(['class' => 'input textarea', 'rows' => 7, 'cols' => 112]) ?>
                    </div>
                </div>
            </div>
            <h3 class="div-line">Выберите свои специализации</h3>
            <div class="account__redaction-section-wrapper">
                <div class="search-task__categories account_checkbox--bottom">
                    <?= \yii\helpers\BaseHtml::activeCheckboxList($model, 'categories_ids', \yii\helpers\ArrayHelper::map($categories, 'id', 'name'), [
                        ['tag' => false, 'inputOptions' => ['class' => 'visually-hidden checkbox__input']]
                    ]) ?>
                </div>
            </div>
            <h3 class="div-line">Безопасность</h3>
            <div class="account__redaction-section-wrapper account__redaction">
                <div class="account__input">
                    <label for="211">Новый пароль</label>
                    <?= $form->field($model, 'new_password', ['template' => "{input}\n{error}"])->input('password', ['class' => 'input textarea']) ?>
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
                    <?= $form->field($model, 'phone', ['template' => "{input}\n{error}"])->input('text', ['class' => 'input textarea']) ?>
                </div>
                <div class="account__input">
                    <label for="214">Skype</label>
                    <?= $form->field($model, 'skype', ['template' => "{input}\n{error}"])->input('text', ['class' => 'input textarea']) ?>
                </div>
                <div class="account__input">
                    <label for="215">Telegram</label>
                    <?= $form->field($model, 'telegram', ['template' => "{input}\n{error}"])->input('text', ['class' => 'input textarea']) ?>
                </div>
            </div>
            <h3 class="div-line">Настройки сайта</h3>
            <h4>Уведомления</h4>
            <div class="account__redaction-section-wrapper account_section--bottom">
                <div class="search-task__categories account_checkbox--bottom">
                    <?= $form->field($model, 'notification_actions', ['template' => "{input}\n{error}"])->checkbox(['label' => 'Новое сообщение']) ?>
                    <?= $form->field($model, 'notification_message', ['template' => "{input}\n{error}"])->checkbox(['label' => 'Действия по заданию']) ?>
                    <?= $form->field($model, 'notification_feedback', ['template' => "{input}\n{error}"])->checkbox(['label' => 'Новый отзыв']) ?>
                </div>
                <div class="search-task__categories account_checkbox account_checkbox--secrecy">
                    <?= $form->field($model, 'private_contacts', ['template' => "{input}\n{error}"])->checkbox(['label' => 'Показывать мои контакты только заказчику']) ?>
                    <?= $form->field($model, 'private_profile', ['template' => "{input}\n{error}"])->checkbox(['label' => 'Не показывать мой профиль']) ?>

                </div>
            </div>
        </div>
        <button class="button" type="submit">Сохранить изменения</button>
    <?php ActiveForm::end(); ?>
</section>
<script src="js/dropzone.js"></script>
<script>
    Dropzone.autoDiscover = false;

    var dropzone = new Dropzone(".dropzone", {url: '/account/photos', maxFiles: 6, parallelUploads: 6, uploadMultiple: true,
        acceptedFiles: 'image/*', previewTemplate: '<a href="#"><img data-dz-thumbnail alt="Фото работы"></a>'});
</script>
