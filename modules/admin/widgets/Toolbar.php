<?php

namespace app\modules\admin\widgets;

use app\models\Option;
use app\models\User;
use app\models\MTS;
use Yii;
use yii\base\Widget;

class Toolbar extends Widget
{

    public function getItems()
    {
        $items = [];
        if (MTS::getTenantId()) {
            // 租赁列表
            $tenantsRawData = Yii::$app->db->createCommand('SELECT [[id]], [[name]], [[language]] FROM {{%tenant}} WHERE [[enabled]] = :enabled AND [[id]] IN (SELECT [[tenant_id]] FROM {{%tenant_user}} WHERE [[user_id]] = :userId AND [[enabled]] = :enabled)')->bindValues([
                    ':enabled' => \app\models\Constant::BOOLEAN_TRUE,
                    ':userId' => Yii::$app->getUser()->getId()
                ])->queryAll();
            if ($tenantsRawData) {
                $tenants = [];
                if (count($tenantsRawData) > 1) {
                    foreach ($tenantsRawData as $data) {
                        $tenants[] = [
                            'label' => '[ ' . Yii::t('language', $data['language']) . " ] {$data['name']}",
                            'url' => ['/default/change-tenant', 'tenantId' => $data['id']],
                        ];
                    }
                }

                $items = [
                    [
                        'label' => '[ ' . Yii::t('language', MTS::getLanguage()) . ' ] ' . MTS::getLanguage() . MTS::getTenantName(),
                        'url' => '###',
                        'options' => ['class' => 'change-tenant'],
                        'items' => $tenants,
                    ],
                ];
            }
        }

        $user = Yii::$app->getUser();
        if (!$user->isGuest) {
            $items[] = [
                'label' => $user->getIdentity()->username . ((MTS::getTenantUserRole() == User::ROLE_ADMINISTRATOR) ? ' [ M ]' : ''),
                'url' => ['default/profile'],
            ];

            $items[] = [
                'label' => Yii::t('app', 'Logout'),
                'url' => ['default/logout'],
                'template' => '<a id="logout" href="{url}">{label}</a>'
            ];
        }

        if (MTS::getTenantId()) {
            $items[] = [
                'label' => Yii::t('app', 'Frontend Page'),
                'url' => 'http://' . MTS::getTenantValue('domainName'),
                'template' => '<a href="{url}" target="_blank">{label}</a>'
            ];
        }

        return $items;
    }

    public function run()
    {
        return $this->render('Toolbar', [
                'items' => $this->getItems(),
        ]);
    }

}
