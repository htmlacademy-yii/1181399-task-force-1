<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@mail' => '@frontend/views/mail',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => false,//true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                '' => 'landing/landing',
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/tasks'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/messages'],
                'tasks' => 'tasks/index',
                'users' => 'users/index',
                'list' => 'list/index',
                'account' => 'account/index',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                'registration' => 'registration/register',
                'login' => 'site/login',
                'logout' => 'landing/logout',
            ],
        ],
        'user' => [
            'identityClass' => \frontend\models\User::class,
            'class' => 'yii\web\User'
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'vk' => [
                    'class' => \yii\authclient\clients\VKontakte::class,
                    'clientId' => '7635089',
                    'clientSecret' => 'A7NibiLbpgQOFd9knP8H',
                    'scope' => ['email']
                ]
            ],
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'redis',
            'port' => 6379,
            'database' => 0,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'redisCache' => [
            'class' => 'yii\redis\Cache',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
        ],
        'viewComposer' => [
            'class' => 'frontend\composers\MainComposer',
        ],
    ],
    'language' => 'ru-RU',
];
