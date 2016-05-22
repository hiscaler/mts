<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('tenant', 'Create Tenant Manage User'), 'url' => ['create-tenant-user']],
    ['label' => Yii::t('app', 'Search'), 'url' => '#'],
];

$baseUrl = Yii::$app->getRequest()->baseUrl . '/admin';
?>

<div class="user-index">

    <?= $this->render('_search', ['model' => $searchModel]) ?>

    <?php
    $session = Yii::$app->getSession();
    if ($session->hasFlash('notice')) {
        echo app\modules\admin\extensions\MessageBox::widget([
            'title' => Yii::t('app', 'Prompt Message'),
            'message' => $session->getFlash('notice'),
            'showCloseButton' => true
        ]);
    }

    Pjax::begin([
        'formSelector' => '#form-user-search',
    ]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['class' => 'serial-number']
            ],
            [
                'attribute' => 'user_group_name',
                'label' => Yii::t('tenantUser', 'User Group'),
                'contentOptions' => ['class' => 'user-group-name']
            ],
            [
                'attribute' => 'username',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model['username'], ['update', 'id' => $model['id']]);
                },
                'contentOptions' => ['class' => 'username']
            ],
            [
                'attribute' => 'nickname',
                'contentOptions' => ['class' => 'username']
            ],
            [
                'attribute' => 'role',
                'format' => 'userRole',
                'contentOptions' => ['class' => 'user-role'],
            ],
            [
                'attribute' => 'rule_name',
                'label' => Yii::t('tenantUser', 'Rule'),
                'contentOptions' => ['class' => 'workflow-rule-name'],
            ],
            'email:email',
            [
                'attribute' => 'enabled',
                'format' => 'boolean',
                'contentOptions' => ['class' => 'boolean enabled-enable-handler pointer']
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{auth} {update} {change-password} {delete}',
                'buttons' => [
                    'auth' => function ($url, $model, $key) use ($baseUrl) {
                        return Html::a(Html::img($baseUrl . '/images/auth.png'), $url, ['data-pjax' => 0, 'class' => 'user-auth', 'data-name' => $model['username']]);
                    },
                    'change-password' => function ($url, $model, $key) use ($baseUrl) {
                        return Html::a(Html::img($baseUrl . '/images/change-password.png'), $url);
                    },
                ],
                'headerOptions' => ['class' => 'last'],
                'contentOptions' => ['class' => 'buttons-4'],
            ],
        ],
    ]);
    Pjax::end();
    ?>

</div>

<?php
$this->registerJs('yadjet.actions.toggle("table td.enabled-enable-handler img", "' . Url::toRoute('toggle') . '");');

$title = Yii::t('app', 'Please choice this user can manager nodes');
$js = <<<EOT
jQuery(document).on('click', 'a.user-auth', function () {
    var t = $(this);
    var url = t.attr('href');
    $.ajax({
        type: 'GET',
        url: url,
        beforeSend: function(xhr) {
            $.fn.lock();
        }, success: function(response) {
            $.dialog({
                id: 'nodes-list',
                title: '{$title}' + ' [ ' + t.attr('data-name') + ' ]',
                content: response,
                lock: true,
                padding: '10px',
                ok: function () {
                    var nodes = $.fn.zTree.getZTreeObj("__ztree__").getCheckedNodes(true);
                    var ids = [];
                    for(var i = 0, l = nodes.length; i < l; i++){
                        ids.push(nodes[i].id);
                    }
                    
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: { choiceNodeIds: ids.toString() },
                        dataType: 'json',
                        beforeSend: function(xhr) {
                            $.fn.lock();
                        }, success: function(response) {
                            if (response.success === false) {
                                $.alert(response.error.message);
                            }
                            $.fn.unlock();
                        }, error: function(XMLHttpRequest, textStatus, errorThrown) {
                            $.alert('[ ' + XMLHttpRequest.status + ' ] ' + XMLHttpRequest.responseText);
                            $.fn.unlock();
                        }
                    });
                }
            });
            $.fn.unlock();
        }, error: function(XMLHttpRequest, textStatus, errorThrown) {
            $.alert('[ ' + XMLHttpRequest.status + ' ] ' + XMLHttpRequest.responseText);
            $.fn.unlock();
        }
    });
    
    return false;
});
EOT;
        $this->registerJs($js);
        