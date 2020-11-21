<?php

namespace frontend\services;

use frontend\models\City;
use frontend\models\User;
use Yii;
use yii\authclient\ClientInterface;

class AuthorizationService
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }


    /**
     * Авторизация через вк.
     */
    public function handle()
    {
        if (!Yii::$app->user->isGuest) {
            return;
        }
        $attributes = $this->client->getUserAttributes();
        $id = $attributes['id'] ?? null;
        $email = $attributes['email'] ?? null;
        $fname = $attributes['first_name'] ?? null;
        $lname = $attributes['last_name'] ?? null;
        $name = "{$fname} {$lname}";

        $user = User::findOne(['vk_id' => $id]);
        if (!$user) {
            $user = $this->registerUser($id, $email, $name);
        }
        $this->authUser($user);
    }

    /**
     * Регистрация через вк.
     *
     * @param $id
     * @param $email
     * @param $name
     * @param null $city
     * @return User
     */
    private function registerUser($id, $email, $name, $city = null)
    {
        $user = User::findAll(['email' => $email]);
        if (count($user) > 0) {
            throw new \InvalidArgumentException("Пользователь с таким email уже зарегистрирован в нашей системе");
        }

        $user = new User();
        $user->name = $name;
        $user->vk_id = $id;
        $user->email = $email;

        $city_id = null;
        if ($city) {
            $city_id = City::findOne(['name' => $city])->name ?? null;
        }

        $user->city_id = $city_id;

        $user->save();
        return $user;
    }

    /**
     * Авторизация через конкретного пользователя.
     *
     * @param User $user
     * @return bool
     */
    private function authUser(User $user)
    {
        return Yii::$app->user->login($user);
    }
}
