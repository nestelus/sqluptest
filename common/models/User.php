<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $role_id
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED     = 0;
    const STATUS_UNCONFIRMED = 1;
    const STATUS_ACTIVE      = 10;
    const TOKEN_RESET        = 'password_reset_token ';
    const TOKEN_CONFIRM      = 'confirm_token';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'string', 'min' => 3, 'max' => 35],
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'match', 'pattern' => '/^[а-яА-ЯёЁa-zA-Z0-9]+$/', 'message' => 'Имя пользователя может содержать только буквы или цифры'],
            ['username', 'required'],
            ['username', 'unique',
                'targetClass' => self::className(),
                'message' => 'Пользователь с таким именем уже существует',
            ],
            // email rules
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            'emailUnique' => [
                'email',
                'unique',
                'targetClass' => self::className(),
                'message' => 'Такой e-mail уже используется ',
            ],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(),
                'targetAttribute' => ['role_id' => 'id']],
            ['status', 'default', 'value' => self::STATUS_UNCONFIRMED],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED,
                    self::STATUS_UNCONFIRMED]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Имя пользователя',
            'status' => 'Статус',
            'email' => 'E-mail',
            'role_id' => 'Роль',
            'role' => 'Роль',
            'password' => 'Пароль',
            'created_at' => 'Создан',
            'updated_at' => 'Изменен',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        switch ($type) {
            case self::TOKEN_RESET:
                return static::findOne(['password_reset_token' => $token, 'status' => self::STATUS_ACTIVE]);
            case self::TOKEN_CONFIRM:
                return static::findOne(['confirm_token' => $token, 'status' => self::STATUS_UNCONFIRMED]);
        }
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
        $expire    = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
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
        return Yii::$app->security->validatePassword($password,
                $this->password_hash);
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
        $this->password_reset_token = Yii::$app->security->generateRandomString().'_'.time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Generates new password reset token
     */
    public function generateConfirmToken()
    {
        $this->confirm_token = Yii::$app->security->generateRandomString().'_'.time();
    }

    public function confirm()
    {
        if ($this->status != self::STATUS_UNCONFIRMED) {
            Yii::$app->session->setFlash('error',
                'Этот пользователь не может быть подтвержден', true);

            return false;
        }
        $this->updateAttributes(['status' => self::STATUS_ACTIVE, 'confirm_token' => null]);
        Yii::$app->session->setFlash('success',
            'Е-mail подтверждён. Теперь Вы можете авторизоваться', true);

        return TRUE;
    }

    public function getRole()
    {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
    }

    public function validateAdmin()
    {
        return ($this->role_id == Role::ROLE_ADMIN);
    }
}
