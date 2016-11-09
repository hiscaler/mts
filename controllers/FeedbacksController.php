<?php

namespace app\controllers;

use app\forms\FeedbackForm;
use app\models\Feedback;
use Yii;

/**
 * 用户留言
 *
 * @author hiscaler <hiscaler@gmail.com>
 */
class FeedbacksController extends Controller
{

    public function actionIndex()
    {
        $model = new FeedbackForm();
        if ($model->load(Yii::$app->getRequest()->post()) && $model->validate()) {
            $columns = [
                'username' => $model->username,
                'tel' => $model->tel,
                'title' => $model->title,
                'message' => $model->message,
                'ip_address' => ip2long(Yii::$app->getRequest()->getUserIP()),
                'status' => Feedback::STATUS_PENDING,
                'tenant_id' => $this->tenantId,
                'created_at' => time(),
                'created_by' => Yii::$app->getUser()->getId(),
            ];
            Yii::$app->getDb()->createCommand()->insert('{{%feedback}}', $columns)->execute();
            Yii::$app->getSession()->setFlash('success', '您的反馈我们已经收到。感谢您的留言。');

            return $this->redirect(['feedbacks/index']);
        }

        return $this->render('index', [
                'model' => $model,
        ]);
    }

}
