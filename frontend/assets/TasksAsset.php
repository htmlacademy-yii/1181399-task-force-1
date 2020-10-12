<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class TasksAsset extends AssetBundle
{
    public $basePath = '@frontend';
    public $css = [
    ];
    public $js = [
        'js/main.js',
        'js/initYmaps.js',
        'js/messenger.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public function __construct($config = [])
    {
        $key = \Yii::$app->params['mapsApiKey'];
        array_unshift($this->js, "https://api-maps.yandex.ru/2.1/?apikey={$key}&lang=ru_RU");
        parent::__construct($config);

    }
}
