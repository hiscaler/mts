<?php

namespace app\widgets;

use yii\db\Query;

/**
 * Description of Banners
 *
 * @author hiscaler
 */
class Banners extends \yii\base\Widget
{

    public function run()
    {
        $items = (new Query())->select(['picture_path AS picture'])
            ->from('{{%slide}}')
            ->where(['tenant_id' => $this->view->context->tenantId])
            ->all();

        return $this->render('Banners', [
                'items' => $items,
        ]);
    }

}
