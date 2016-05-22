<?php

use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\widgets\DetailView;

$formatter = Yii::$app->getFormatter();
$tab = Yii::$app->getRequest()->get('tab', 'detail');
?>

<div class="tabs-common">
    <ul id="tenant-tabs">
        <?php
        foreach ($items as $item):
            $cssClass = "panel-tenant-{$tab}" == $item['id'] ? ' class="active"' : '';
            ?>
            <li<?php echo $cssClass; ?>><?php echo Html::a($item['label'], $item['url'], ['data-key' => $item['id']]); ?></li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="panels">
    <div id="panel-tenant-detail" class="tenant-view panel" style="<?= $tab == 'detail' ? '' : 'display: none' ?>">

        <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                'key',
                'name',
                'domain_name',
                [
                    'attribute' => 'language',
                    'value' => Yii::t('language', $model['language']),
                ],
                [
                    'attribute' => 'timezone',
                    'value' => Yii::t('timezone', $model['timezone']),
                ],
                'date_format',
                'time_format',
                'datetime_format',
                'enabled:boolean',
                'description',
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
//                [
//                    'attribute' => 'deleted_by',
//                    'value' => $model['deleter']['nickname']
//                ],
//                'deleted_at:datetime',
            ],
        ])
        ?>

    </div>

    <div id="panel-tenant-modules" class="tenant-view panel" style="<?= $tab == 'modules' ? '' : 'display: none' ?>">
        <div class="clearfix">
            <ul class="list">
                <?php foreach ($model['modules'] as $moduleName): ?>
                    <li><?= Yii::t('model', Inflector::camel2words(str_replace('common-models-', '', $moduleName))) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div id="panel-tenant-users" class="tenant-view panel" style="<?= $tab == 'users' ? '' : 'display: none' ?>">
        <div class="grid-view clearfix">
            <table class="table table-striped table-bordered"><thead>
                    <tr>
                        <th>#</th>
                        <th><?= Yii::t('user', 'Username') ?></th>
                        <th><?= Yii::t('user', 'Nickname') ?></th>
                        <th><?= Yii::t('user', 'Email') ?></th>
                        <th><?= Yii::t('user', 'Group') ?></th>
                        <th><?= Yii::t('user', 'Role') ?></th>
                        <th><?= Yii::t('user', 'Rule') ?></th>
                        <th><?= Yii::t('app', 'Enabled') ?></th>
                        <th class="data-status last"><?= Yii::t('app', 'Status') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($model['users'] as $i => $user): ?>
                        <tr>
                            <td class="serial-number"><?= $i + 1 ?></td>
                            <td class="username"><?= $user['username'] ?></td>
                            <td class="username"><?= $user['nickname'] ?></td>
                            <td class="email"><?= $user['email'] ?></td>
                            <td class="user-group-name"><?= $user['group_name'] ?></td>
                            <td class="user-role"><?= $formatter->asUserRole($user['role']) ?></td>
                            <td class="rule"><?= $user['rule_name'] ?></td>
                            <td class="boolean"><?= $formatter->asBoolean($user['enabled']) ?></td>
                            <td class="data-status"><?= $formatter->asUserStatus($user['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>    
    
</div>

<?php
$js = <<<'EOT'
    $(document).on('click', '#tenant-tabs li a', function () {
        $t = $(this);
        $('.panel').hide();
        $('#' + $t.attr('data-key')).show();
        $t.parent().siblings().removeClass('active').end().addClass('active');

        return false;
    });
EOT;
$this->registerJs($js);
