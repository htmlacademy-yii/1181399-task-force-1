<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "bookmarks".
 *
 * @property int $id
 * @property int $user_id
 * @property int $bookmark_user_id
 *
 * @property User $bookmarkUser
 * @property User $user
 */
class Bookmark extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bookmarks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'bookmark_user_id'], 'required'],
            [['user_id', 'bookmark_user_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['bookmark_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['bookmark_user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'bookmark_user_id' => 'Bookmark User ID',
        ];
    }

    /**
     * Gets query for [[BookmarkUser]].
     *
     * @return \yii\db\ActiveQuery|UsersQuery
     */
    public function getBookmarkUser()
    {
        return $this->hasOne(User::class, ['id' => 'bookmark_user_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|UsersQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return BookmarksQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BookmarksQuery(get_called_class());
    }
}
