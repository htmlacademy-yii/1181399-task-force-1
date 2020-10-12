<?php


namespace frontend\modules\api\controllers;


use yii\filters\AccessControl;
use yii\web\Response;

class SecuredRestController extends \yii\rest\ActiveController
{
    public function init()
    {
        parent::init();

        \Yii::$app->response->format = Response::FORMAT_JSON;
    }


    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }
}
