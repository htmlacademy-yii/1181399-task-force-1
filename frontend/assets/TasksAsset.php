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
        //  'js/messenger.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
