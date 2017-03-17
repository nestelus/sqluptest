<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title                   = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?=
        Html::a('Редактировать', ['update', 'id' => $model->id],
            ['class' => 'btn btn-primary'])
        ?>

    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email:email',
            [
                'attribute' => 'status',
                'value' => function($model) {
                    switch ($model->status) {
                        case User::STATUS_ACTIVE:
                            return 'Активен';
                        case User::STATUS_UNCONFIRMED:
                            return 'Не подтвержден';
                        case User::STATUS_DELETED:
                            return 'Заблокирован';
                    }
                }
            ],
            'created_at:datetime',
            'updated_at:datetime',
            'role.title',
        ],
    ])
    ?>

</div>
