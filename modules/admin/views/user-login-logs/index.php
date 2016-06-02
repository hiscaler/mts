<?php

use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserLoginLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'User Login Logs');
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Search'), 'url' => '#'],
];
?>
<div class="user-login-log-index">

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    Pjax::begin([
        'formSelector' => '#user-login-logs-search-form',
    ]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['class' => 'serial-number']
            ],
            [
                'attribute' => 'user.username',
                'contentOptions' => ['class' => 'username'],
            ],
            [
                'attribute' => 'login_at',
                'format' => 'datetime',
                'contentOptions' => ['class' => 'datetime'],
            ],
            [
                'attribute' => 'login_ip',
                'value' => function($model) {
                    return long2ip($model['login_ip']);
                },
                'contentOptions' => ['class' => 'ip-address'],
            ],
            [
                'attribute' => 'client_informations',
                'headerOptions' => ['class' => 'last']
            ],
        ],
    ]);
    Pjax::end();
    ?>

</div>
