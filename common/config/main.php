<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                '' => 'landing/landing',
                'tasks' => 'tasks/index',
                'users' => 'users/index',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                'registration' => 'registration/register',
                'login' => 'site/login',
                'logout' => 'landing/logout'
            ],
        ],
        'user' => [
            'class' => \frontend\helpers\User::class,
            'identityClass' => \frontend\models\User::class,
        ],
    ],
    'language' => 'ru-RU',
];
