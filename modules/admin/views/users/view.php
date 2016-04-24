<?php

use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->username;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Update'), 'url' => ['update', 'id' => $model->id]],
];
?>

<div class="tabs-common">
    <ul id="user-tabs">
        <li class="active"><a href="###" data-key="panel-user-detail"><?= Yii::t('user', 'Base Informations') ?></a></li>
        <li><a href="###" data-key="panel-user-tenant"><?= Yii::t('user', "Management's Sites") ?><em class="badges badges-red"><?= count($model['tenants']) ?></em></a></li>
        <li><a href="###" data-key="panel-login-logs"><?= Yii::t('app', 'Login Logs') ?></a></li>
    </ul>
</div>

<div class="panels">
    <div id="panel-user-detail" class="panel">    

        <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
//                'type:userType',
                'username',
                'nickname',
                'email:email',
//                'role:userRole',
//                'status:userStatus',
                'register_ip',
                'login_count',
                'last_login_ip',
                'last_login_datetime:datetime',
//                'last_change_password_time:datetime',
                [
                    'attribute' => 'created_by',
                    'value' => $model['creater']['nickname']
                ],
                'created_at:datetime',
                [
                    'attribute' => 'updated_by',
                    'value' => $model['updater']['nickname']
                ],
                'updated_at:datetime',
                [
                    'attribute' => 'deleted_by',
                    'value' => $model['deleter']['nickname']
                ],
                'deleted_at:datetime',
            ],
        ])
        ?>

    </div>

    <div id="panel-user-tenant" class="panel" style="display: none">
        <div class="grid-view clearfix">
            <table class="table table-striped table-bordered"><thead>
                    <tr>
                        <th>#</th>
                        <th><?= Yii::t('tenant', 'Key') ?></th>
                        <th><?= Yii::t('tenant', 'Name') ?></th>
                        <th><?= Yii::t('tenant', 'Domain Name') ?></th>
                        <th><?= Yii::t('tenant', 'Language') ?></th>
                        <th class="last"><?= Yii::t('tenant', 'Description') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($model['tenants'] as $i => $tenant): ?>
                        <tr>
                            <td class="serial-number"><?= $i + 1 ?></td>
                            <td class="tenant-key"><?= $tenant['key'] ?></td>
                            <td class="tenant-name"><?= $tenant['name'] ?></td>
                            <td class="domain-name"><?= $tenant['domain_name'] ?></td>
                            <td class="tenant-language"><?= $tenant['language'] ?></td>
                            <td><?= $tenant['description'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="panel-login-logs" class="panel" style="display: none">
        <?php
        Pjax::begin();
        echo GridView::widget([
            'dataProvider' => $loginLogsDataProvider,
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'contentOptions' => ['class' => 'serial-number']
                ],
                [
                    'attribute' => 'login_at',
                    'format' => 'datetime',
                    'contentOptions' => ['class' => 'datetime'],
                ],
                [
                    'attribute' => 'login_ip',
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

</div>
