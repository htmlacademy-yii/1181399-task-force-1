<?php

namespace frontend\controllers;

use frontend\models\City;
use frontend\models\requests\RegistrationForm;
use frontend\models\User;
use Yii;
use yii\web\Controller;

class RegistrationController extends Controller
{
    /**
     * Страница регистрации пользователя
     *
     * @return string|\yii\web\Response
     */
    public function actionRegister()
    {
        $request = new RegistrationForm();
        $request->load(Yii::$app->request->post());

        $cities = City::find()->all();

        if (Yii::$app->request->isPost) {
            if ($request->validate()) {
                $user = $this->registerUser($request);
                if ($user) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('register', compact('request', 'cities'));
    }

    public function registerUser(RegistrationForm $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Yii::$app->security->generatePasswordHash($request->password);
        $user->city_id = $request->city;
        return $user->save();
    }
}
