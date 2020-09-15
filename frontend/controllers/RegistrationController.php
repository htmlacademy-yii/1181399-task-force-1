<?php

namespace frontend\controllers;

use frontend\models\City;
use frontend\models\requests\RegistrationForm;
use frontend\models\User;
use Yii;
use yii\web\Controller;

class RegistrationController extends Controller
{
    public function actionRegister()
    {
        $request = new RegistrationForm();
        $request->load(Yii::$app->request->post());

        $cities = City::find()->all();

        if (Yii::$app->request->method === 'POST') {
            if (!$request->validate()) {
                return $this->render('register', compact('request', 'cities'));
            }
            $user = $this->registerUser($request);
            if ($user) {
                return $this->redirect('/');
            }
        }

        return $this->render('register', compact('request', 'cities'));
    }

    public function registerUser(RegistrationForm $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = password_hash($request->password, PASSWORD_DEFAULT);
        $user->city_id = $request->city;
        return $user->save();
    }
}
