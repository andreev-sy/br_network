<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;

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
 * @property string $password write-only password
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Collection[] $collections
 * @property Venues[] $venues
 * @property Venues[] $venues0
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    public $status_list = [
        0 => 'Deleted',
        9 => 'No active',
        10 => 'Active',
    ];

    public $files;

    public $password = ''; 

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
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
            [['username', 'auth_key', 'password_hash', 'fullname'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['photo', 'photo_path'], 'string'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'role', 'fullname', 'phone', 'verification_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['files'], 'file'],

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
            'password' => Yii::t('app', 'Пароль'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'email' => Yii::t('app', 'Email'),
            'status' => Yii::t('app', 'Статус'),
            'files' => Yii::t('app', 'Фото'),
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
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

     /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

      /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
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

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $image = UploadedFile::getInstancesByName('User[files]');
            if(!empty($image[0])){
                $image = $image[0];
                if(!empty($this->photo) and file_exists($this->photo))
                    unlink($this->photo);

                $photo = '/var/www/br_network/frontend/web/user_photo/' . $this->username.'.'.$image->extension;
                $photo_path = '/user_photo/' . $this->username.'.'.$image->extension;
                if($image->saveAs($photo)){
                    $this->photo = $photo;
                    $this->photo_path = $photo_path;
                }
            }

            return true;
        }

        return false;
    }

    
}
