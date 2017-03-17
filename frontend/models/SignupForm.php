<?php

namespace frontend\models;

use yii\base\Model;
use common\models\User;
use common\models\Role;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $phone;
    public $password;
    public $verifyCode;
    public $confirmPassword;

    const SCENARIO_REGISTER = 'register';
    const SCENARIO_VALIDATE = 'validate';

    public function scenarios()
    {
        $scenarios                          = parent::scenarios();
        $scenarios[self::SCENARIO_REGISTER] = ['username', 'email', 'password', 'verifyCode',
            'phone', 'confirmPassword'];
        $scenarios[self::SCENARIO_VALIDATE] = ['username', 'email', 'password', 'confirmPassword',
            'phone'];

        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $user = User::className();
        return [
            ['username', 'trim'],
            ['username', 'string', 'min' => 3, 'max' => 35],
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'match', 'pattern' => '/^[а-яА-ЯёЁa-zA-Z0-9]+$/', 'message' => 'Имя пользователя может содержать только буквы или цифры'],
            ['username', 'required'],
            ['username', 'unique',
                'targetClass' => $user,
                'message' => 'Пользователь с таким именем уже существует',
            ],
            // email rules
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            'emailUnique' => [
                'email',
                'unique',
                'targetClass' => $user,
                'message' => 'Такой e-mail уже используется ',
            ],
            ['phone', 'required'],
            // password rules
            ['password', 'required'],
            ['password', 'string', 'min' => 6, 'max' => 72],
            ['confirmPassword', 'required', 'message' => 'Необходимо повторить пароль'],
            ['confirmPassword', 'compare', 'compareAttribute' => 'password', 'message' => 'Введенный и повторенный пароли не совпадают'],
            ['verifyCode', 'captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',
            'password' => 'Пароль',
            'phone' => 'Телефон',
            'verifyCode' => 'Введите символы на картинке',
            'confirmPassword' => 'Повторите пароль',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {

        if (!$this->validate()) {
            return null;
        }

        $user           = new User();
        $user->username = $this->username;
        $user->email    = $this->email;
        $user->phone    = $this->phone;
        $user->role_id  = Role::ROLE_DEFAULT;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateConfirmToken();
        $user->status   = User::STATUS_UNCONFIRMED;

        if ($user->save() && $this->sendEmail($user)) {
            Yii::$app->session->setFlash('success',
                'На Ваш e-mail выслано письмо с дальнейшими инструкциями', true);
            return true;
        }
        return null;
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    private function sendEmail($user)
    {
        /* @var $user User */
        if (!$user) {
            return false;
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        return
                Yii::$app
                ->mailer
                ->compose(
                    ['html' => 'confirmRegister-html', 'text' => 'confirmRegister-text'],
                    ['user' => $user]
                )
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name.' noreply'])
                ->setTo($this->email)
                ->setSubject('Подтвердите регистрацию на '.Yii::$app->name)
                ->send();
    }
}
