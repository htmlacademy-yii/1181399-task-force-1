<?php

namespace frontend\controllers;

use frontend\models\Bookmark;
use frontend\models\Category;
use frontend\models\requests\UsersSearchForm;
use frontend\models\User;
use models\Book;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class UsersController extends SecuredController
{
    /**
     * Просмотр списка пользователей
     *
     * @return string
     */
    public function actionIndex()
    {
        $form = new UsersSearchForm();
        $form->load(Yii::$app->request->get());

        [$users, $pages] = $form->getUsersFromForm();
        $categories = Category::find()->all();

        return $this->render('browse',
             [
                 'users' => $users,
                 'request' => $form,
                 'categories' => $categories,
                 'pages' => $pages
             ]
        );
    }

    /**
     * Просмотр конкретного пользователя
     *
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $user = User::findOne($id);
        if (!$user) {
            throw new NotFoundHttpException("Пользователь с id {$id} не найден");
        }

        if ($user->isAuthor()) {
            throw new NotFoundHttpException("Пользователь с id {$id} является автором");
        }
        $user->views += 1;
        $user->save();

        return $this->render('show', ['user' => $user]);
    }

    /**
     * Добавление или удаление пользователя (в зависимости от состояния) в избранное.
     *
     * @param $id
     * @return \yii\web\Response
     */
    public function actionBookmark($id)
    {
        $userBookmark = Bookmark::find()->where(['user_id' => Yii::$app->user->getId()])
            ->where(['bookmark_user_id' => $id])
            ->one();

        if ($userBookmark) {
            Bookmark::deleteAll(['user_id' => Yii::$app->user->getId(), 'bookmark_user_id' => $id]);
            return $this->redirect(['users/view', 'id' => $id]);
        }

        $bookmark = new Bookmark();
        $bookmark->user_id = Yii::$app->user->getId();
        $bookmark->bookmark_user_id = $id;
        $bookmark->save();
        return $this->redirect(['users/view', 'id' => $id]);
    }
}
