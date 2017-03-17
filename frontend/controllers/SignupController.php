<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\controllers;

use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;
use frontend\models\SignupForm;
use yii\web\Response;
use yii\widgets\ActiveForm;
use common\models\User;
use common\models\LoginForm;

/**
 * Description of SignupController
 *
 * @author Vladimir
 */
class SignupController extends Controller
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'frontend\models\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();

//        var_dump(Yii::$app->request->post());
//        die();


        if ($model->load(Yii::$app->request->post())) {

            $model->scenario = SignupForm::SCENARIO_REGISTER;

            if ($model->signup()) {

                return $this->goHome();
            }
        }

        return $this->render('signup',
                [
                'model' => $model,
        ]);
    }

    public function actionSignupValidate()
    {
        $model = new SignupForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            $model->scenario            = SignupForm::SCENARIO_VALIDATE;
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    public function actionConfirmRegister($token)
    {
        /** @var $user common\models\User * */
        $user = User::findIdentityByAccessToken($token, User::TOKEN_CONFIRM);

        if ($user) {
            $user->confirm();
            return $this->redirect(['login']);
        } else {
            return $this->goHome();
        }
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->render('welcome');
        } else {
            return $this->render('login',
                    [
                    'model' => $model,
            ]);
        }
    }
}
