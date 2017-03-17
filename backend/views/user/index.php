<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use common\models\Role;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel common\models\searchUser */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>



    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'username',
            [
                'attribute' => 'role.title',
                'filter' => Html::dropDownList('searchUser[role_id]',
                    $searchModel->role_id,
                    ArrayHelper::map(Role::find()->all(), 'id', 'title'),
                    ['class' => 'form-control', 'prompt' => 'Все']),
            ],
            'email:email',
            'phone',
            [
                'attribute' => 'status',
                'filter' => Html::dropDownList('searchUser[status]',
                    $searchModel->status,
                    [
                    User::STATUS_ACTIVE => 'Активны',
                    User::STATUS_UNCONFIRMED => 'Не подтверждены',
                    User::STATUS_DELETED => 'Заблокированы',
                    ], ['class' => 'form-control', 'prompt' => 'Все']),
                'content' => function($model) {
                switch ($model->status) {
                    case User::STATUS_ACTIVE:
                        return 'Активен';
                    case User::STATUS_UNCONFIRMED:
                        return 'Не подтвержден';
                    case User::STATUS_DELETED:
                        return 'Заблокирован';
                }
                return;
            }
            ]
            ,
            'created_at:datetime',
            // 'updated_at',
            // 
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
    <?php Pjax::end(); ?>
</div>
