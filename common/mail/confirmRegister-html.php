<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['signup/confirm-register',
    'token' => $user->confirm_token]);
?>
<div class="password-reset">
    <p>Здравствуйте <?= Html::encode($user->username) ?>,</p>

    <p>Вы (или кто-то другой с Вашего e-mail) зарегистрировались на сайте <?= Html::a(Yii::$app->name,
    Yii::$app->getHomeUrl())
?></p>

    <p>Для завершения регистрации пройдите по ссылке ниже</p>

    <p><?= Html::a(Html::encode($confirmLink), $confirmLink) ?></p>
</div>
