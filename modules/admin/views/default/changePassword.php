<?php
/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->params['breadcrumbs'][] = Yii::t('app', 'Change Password');
?>

<div class="user-create">

    <?=
    $this->render('_changePasswordForm', [
        'user' => $user,
        'model' => $model,
    ]);
    ?>

</div>
