<?php
/* @var $this yii\web\View */
/* @var $user common\models\User */


$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['signup/confirm-register',
    'token' => $user->confirm_token]);
?>
Здравствуйте <?= $user->username ?>,

Вы (или кто-то другой с Вашего e-mail) зарегистрировались на сайте <?= Yii::$app->name ?>
(<?= Yii::$app->getHomeUrl() ?>)
?>
Для завершения регистрации пройдите по ссылке ниже
<?= $confirmLink ?>
