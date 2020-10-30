<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>
<h2>У вас новое уведомление!</h2>
<?= $content ?>
<?= Url::toRoute(['tasks/view', 'id' => $taskId]) ?>
