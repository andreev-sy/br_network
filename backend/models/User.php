<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string $email
 * @property int $status
 * @property string|null $photo
 * @property string|null $photo_path
 * @property string|null $role
 * @property string|null $fullname
 * @property string|null $phone
 * @property string|null $verification_token
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Collection[] $collections
 * @property Venues[] $venues
 * @property Venues[] $venues0
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['photo', 'photo_path'], 'string'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'role', 'fullname', 'phone', 'verification_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Логин'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'email' => Yii::t('app', 'Email'),
            'status' => Yii::t('app', 'Status'),
            'photo' => Yii::t('app', 'Фото'),
            'photo_path' => Yii::t('app', 'Photo Path'),
            'role' => Yii::t('app', 'Роль'),
            'fullname' => Yii::t('app', 'Полное имя'),
            'phone' => Yii::t('app', 'Телефон'),
            'verification_token' => Yii::t('app', 'Verification Token'),
            'created_at' => Yii::t('app', 'Дата создания'),
            'updated_at' => Yii::t('app', 'Дата изменения'),
        ];
    }

    /**
     * Gets query for [[Collections]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCollections()
    {
        return $this->hasMany(Collection::className(), ['manager_user_id' => 'id']);
    }

    /**
     * Gets query for [[Venues]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesManager()
    {
        return $this->hasMany(Venues::className(), ['manager_user_id' => 'id']);
    }

    /**
     * Gets query for [[Venues0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesVendor()
    {
        return $this->hasMany(Venues::className(), ['vendor_user_id' => 'id']);
    }

    public static function getMap()
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'fullname');
    }

}
