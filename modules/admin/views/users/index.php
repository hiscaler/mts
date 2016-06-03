<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use backend\components\MessageBox;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Search'), 'url' => '#'],
];

$baseUrl = Yii::$app->getRequest()->getBaseUrl() . '/admin';
?>

<div class="user-index">

    <?= $this->render('_search', ['model' => $searchModel]) ?>

    <?php
    $session = Yii::$app->getSession();
    if ($session->hasFlash('notice')) {
        echo MessageBox::widget([
            'title' => '提示信息',
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
            'email:email',
//            [
//                'attribute' => 'role',
//                'format' => 'userRole',
//                'contentOptions' => ['class' => 'center'],
//            ],
//            [
//                'attribute' => 'status',
//                'format' => 'userStatus',
//                'contentOptions' => ['class' => 'data-status'],
//            ],
            [
                'attribute' => 'register_ip',
                'contentOptions' => ['class' => 'ip-address'],
            ],
            [
                'attribute' => 'login_count',
                'contentOptions' => ['class' => 'number'],
            ],
            [
                'attribute' => 'last_login_datetime',
                'format' => 'datetime',
                'contentOptions' => ['class' => 'datetime'],
            ],
            [
                'attribute' => 'last_login_ip',
                'contentOptions' => ['class' => 'ip-address'],
            ],
            [
                'attribute' => 'last_change_password_time',
                'format' => 'datetime',
                'contentOptions' => ['class' => 'datetime'],
            ],
            [
                'attribute' => 'created_by',
                'value' => function ($model) {
                    return $model['creater']['nickname'];
                },
                'contentOptions' => ['class' => 'username']
            ],
            [
                'attribute' => 'created_at',
                'format' => 'date',
                'contentOptions' => ['class' => 'date']
            ],
            [
                'attribute' => 'updated_by',
                'value' => function ($model) {
                    return $model['updater']['nickname'];
                },
                'contentOptions' => ['class' => 'username']
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'date',
                'contentOptions' => ['class' => 'date']
            ],
            [
                'attribute' => 'deleted_by',
                'value' => function ($model) {
                    return $model['deleter']['nickname'];
                },
                'contentOptions' => ['class' => 'username']
            ],
            [
                'attribute' => 'deleted_at',
                'format' => 'date',
                'contentOptions' => ['class' => 'date']
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {change-password} {auth} {delete}',
                'buttons' => [
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
$js = <<<'EOT'
jQuery(document).on('click', 'a.user-auth', function () {
    var $this = $(this);
    $.ajax({
        type: 'GET',
        url: $this.attr('href'),
        beforeSend: function(xhr) {
            $.fn.lock();
        }, success: function(response) {
            $.dialog({
                title: '请选择该用户可管理的节点',
                content: response,
                lock: true,
                padding: '10px',
                ok: function () {
                    var choiceObject = $('#jsTree_w0').jstree('get_checked', true);
                    var ids = [];
                    $.each(choiceObject, function(i, o) {
                        ids.push(o.id);
                    });
                    $.ajax({
                        type: 'POST',
                        url: $this.attr('href'),
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

$js = <<<'EOT'
jQuery(document).on('click', 'a.btn-remove', function () {
    var $this = $(this);
    $.ajax({
        type: 'POST',
        url: $this.attr('href'),
        dataType: 'json',
        beforeSend: function(xhr) {
            $.fn.lock();
        }, success: function(response) {
            if (response.success) {
                $('.image-preview').remove();
            }
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
