<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Role;
use common\models\User;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'username', ['enableAjaxValidation' => true])
    ?>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <?=
            $form->field($model, 'status')->dropDownList([
                User::STATUS_ACTIVE => 'Активен',
                User::STATUS_UNCONFIRMED => 'Не подтвержден',
                User::STATUS_DELETED => 'Заблокирован',
            ])
            ?>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <?=
                $form->field($model, 'role_id')
                ->dropDownList(ArrayHelper::map(Role::find()->all(), 'id',
                        'title'))
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <?=
            $form->field($model, 'email', ['enableAjaxValidation' => true])
            ?>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <?=
            $form->field($model, 'phone')->widget(MaskedInput::className(),
                [
                'mask' => '(999) 999-9999',
                'options' => [

                    'class' => 'form-control',
                ]
            ])
            ?>

        </div>
    </div>

    <div class="form-group">
        <?=
        Html::submitButton('Сохранить', ['class' => 'btn btn-success'])
        ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
