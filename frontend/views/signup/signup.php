<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Url;

$this->title                   = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Все поля обязательны для заполнения</p>

    <div class="row">
        <div class="col-lg-5">
            <?php
            $form                          = ActiveForm::begin([
                    'id' => 'form-signup',
                    'validationUrl' => Url::to(['signup/signup-validate']),
                    'enableClientValidation' => false,
                    'validateOnSubmit' => false,
            ]);
            ?>

            <?=
            $form->field($model, 'username', ['enableAjaxValidation' => true])->textInput(['autofocus' => true])
            ?>

            <?=
            $form->field($model, 'email', [ 'enableAjaxValidation' => true])
            ?>

            <?= $form->field($model, 'password')->passwordInput() ?>
            <?=
            $form->field($model, 'confirmPassword',
                [ 'enableAjaxValidation' => true])->passwordInput()
            ?>
            <?=
            $form->field($model, 'verifyCode')->widget(Captcha::className(),
                [
                'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
            ])
            ?>
            <div class="form-group">
                <?=
                Html::submitButton('Зарегистрироваться',
                    ['class' => 'btn btn-primary', 'name' => 'signup-button'])
                ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
