<?php

namespace frontend\controllers;

use frontend\models\Category;
use frontend\models\City;
use frontend\models\requests\AccountForm;
use frontend\models\User;
use Yii;

class AccountController extends SecuredController
{
    public function actionIndex()
    {
        $model = new AccountForm();
        $model->load(Yii::$app->request->post());

        if (Yii::$app->request->isPost && $model->validate()) {
            $model->save();
        }

        if ($model->hasErrors()) {
            var_dump($model->getErrors());
            die();
        }

        $cities = City::find()->all();
        /** @var User $user */
        $user = Yii::$app->user->getIdentity();
        $model->city_id = $user->city_id;
        $categories = Category::find()->all();
        $model->specializations = $user->categories;

        return $this->render('account',
             [
                 'user' => $user,
                 'model' => $model,
                 'cities' => $cities,
                 'categories' => $categories
             ]
        );
    }
}
