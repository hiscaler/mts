<?php

namespace app\modules\admin\widgets;

use common\models\BaseCode;
use app\models\Tenant;
use app\models\User;
use app\models\MTS;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

class DashboardControlPanel extends Widget
{

    public $title;

    public function init()
    {
        parent::init();
        $this->title = Yii::t('app', 'Site Management');
    }

    public function getItems()
    {
        $items = [];
        $controller = $this->view->context;
        $controllerId = $controller->id;
//        $tenantModules = Tenant::modules();
        $tenantModules = [];
        $contentModules = isset(Yii::$app->params['contentModules']) ? Yii::$app->params['contentModules'] : [];
        if (MTS::getTenantUserRole() !== User::ROLE_ADMINISTRATOR) {
            // Basic code
//            $i = 0;
//            $basicCodeType = null;
//            foreach (BaseCode::typeOptions() as $key => $value) {
//                if ($i == 0) {
//                    $basicCodeType = $key;
//                    break;
//                }
//            }
            // 系统管理
            $systemManageItems = [
                'label' => Yii::t('app', 'Tenants'),
                'url' => ['tenants/index'],
                'active' => in_array($controllerId, ['users', 'user-groups']),
                'items' => [
                    [
                        'label' => Yii::t('app', 'Tenants'),
                        'url' => ['tenants/index'],
                        'active' => $controllerId == 'tenants',
                    ],
                    [
                        'label' => Yii::t('app', 'Access Token'),
                        'url' => ['users/index'],
                        'active' => $controllerId == 'users',
                    ],
                    [
                        'label' => Yii::t('app', 'Labels'),
                        'url' => ['labels/index'],
                        'active' => $controllerId == 'labels',
                    ],
                    [
                        'label' => Yii::t('app', 'Users'),
                        'url' => ['users/index'],
                        'active' => $controllerId == 'users',
                    ],
                    [
                        'label' => Yii::t('app', '节点管理'),
                        'url' => ['nodes/index'],
                        'active' => $controllerId == 'nodes',
                    ],
                ],
            ];

            // User manage items     
            $userManageItems = [
                'label' => Yii::t('app', 'Users'),
                'url' => ['users/index'],
                'active' => in_array($controllerId, ['users', 'user-groups']),
                'items' => [
                    [
                        'label' => Yii::t('app', 'User Groups'),
                        'url' => ['/user-groups/index'],
                        'active' => $controllerId == 'user-groups',
                    ],
                    [
                        'label' => Yii::t('app', 'Users'),
                        'url' => ['users/index'],
                        'active' => $controllerId == 'users',
                    ]
                ],
            ];
            if (isset(Yii::$app->params['enableFrontendUser']) && Yii::$app->params['enableFrontendUser']) {
                $users = [];
                $userType = ArrayHelper::getValue($_GET, 'UserSearch.type');
                foreach (User::typeOptions() as $key => $value) {
                    $users[] = [
                        'label' => $value,
                        'url' => ['/users/index', 'UserSearch[type]' => $key],
                        'active' => $controllerId == 'users' && $key == $userType,
                    ];
                }
                $userManageItems['items'] = $users;
            }

            // 自定义属性
            if (in_array('common-models-Attribute', $tenantModules)) {
                $attributes = [
                    'label' => Yii::t('app', 'Attributes'),
                    'url' => ['/attributes/index'],
                    'active' => in_array($controllerId, ['attributes', 'entity-attributes']),
                ];
                $attributeModelNames = Yii::$app->db->createCommand('SELECT DISTINCT([[entity_name]]) FROM {{%entity_attribute}} WHERE [[tenant_id]] = :tenantId')->bindValue(':tenantId', MTS::getTenantId())->queryColumn();
                if ($attributeModelNames) {
                    $attributeChildren = [];
                    $modelName = ArrayHelper::getValue($_GET, 'modelName');
                    foreach ($attributeModelNames as $name) {
                        $attributeChildren[] = [
                            'label' => Yii::t('app', isset($contentModules[$name]['label']) ? $contentModules[$name]['label'] : $name),
                            'url' => ['/entity-attributes/entities', 'modelName' => $name],
                            'active' => $controllerId == 'entity-attributes' && $modelName == $name,
                        ];
                    }
                    if ($attributeChildren) {
                        $attributes['items'] = $attributeChildren;
                    }
                }
            } else {
                $attributes = [];
            }

            // 基本设置
            $baseConfigItems = [
                'label' => Yii::t('app', 'Basic Settings'),
                'url' => ['/lookups/index'],
                'active' => in_array($controllerId, ['ad-spaces', 'ads', 'lookups', 'group-options', 'ip-access-rules', 'basic-codes', 'file-upload-configs', 'language-messages', 'grid-column-configs', 'nodes', 'tags']),
                'items' => [
                    [
                        'label' => Yii::t('app', 'Lookups'),
                        'url' => ['/lookups/index'],
                        'active' => $controllerId == 'lookups',
                        'visible' => in_array('common-models-Lookup', $tenantModules)
                    ],
                    [
                        'label' => Yii::t('app', 'Group Options'),
                        'url' => ['/group-options/index'],
                        'active' => $controllerId == 'group-options',
                        'visible' => in_array('common-models-GroupOption', $tenantModules)
                    ],
                    [
                        'label' => Yii::t('app', 'File Upload Configs'),
                        'url' => ['/file-upload-configs/index'],
                        'active' => $controllerId == 'file-upload-configs',
                        'visible' => in_array('common-models-FileUploadConfig', $tenantModules)
                    ],
                    [
                        'label' => Yii::t('app', 'IP Access Rules'),
                        'url' => ['/ip-access-rules/index'],
                        'active' => $controllerId == 'ip-access-rules',
                        'visible' => in_array('common-models-IpAccessRule', $tenantModules)
                    ],
                    [
                        'label' => Yii::t('app', 'Nodes'),
                        'url' => ['/nodes/index'],
                        'active' => $controllerId == 'nodes',
                        'visible' => in_array('common-models-Node', $tenantModules)
                    ],
                    [
                        'label' => Yii::t('app', 'Tags'),
                        'url' => ['/tags/index'],
                        'active' => $controllerId == 'tags',
                        'visible' => in_array('common-models-Tag', $tenantModules)
                    ],
                    [
                        'label' => Yii::t('app', 'Ad Spaces'),
                        'url' => ['/ad-spaces/index'],
                        'active' => $controllerId == 'ad-spaces',
                        'visible' => in_array('common-models-AdSpace', $tenantModules)
                    ],
                    [
                        'label' => Yii::t('app', 'Ads'),
                        'url' => ['/ads/index'],
                        'active' => $controllerId == 'ads',
                        'visible' => in_array('common-models-Ad', $tenantModules)
                    ],
                ],
            ];
            $hasChildren = false;
            foreach ($baseConfigItems['items'] as $config) {
                if ($config['visible']) {
                    $hasChildren = true;
                    break;
                }
            }
            if ($hasChildren == false) {
                $baseConfigItems = [];
            }

            $items = ArrayHelper::merge($items, [
                    $systemManageItems,
                    $baseConfigItems,
                    $attributes,
                    [
                        'label' => Yii::t('app', 'Meta'),
                        'url' => ['/meta/index'],
                        'active' => $controllerId == 'meta',
                        'visible' => in_array('common-models-Meta', $tenantModules)
                    ],
                    $userManageItems,
                    [
                        'label' => Yii::t('app', 'Members'),
                        'url' => ['/members/index'],
                        'active' => $controllerId == 'members',
                    ],
                    [
                        'label' => Yii::t('app', 'User Login Logs'),
                        'url' => ['/user-login-logs/index'],
                        'active' => $controllerId == 'user-login-logs',
                    ],
                    [
                        'label' => Yii::t('app', 'Workflow Rules'),
                        'url' => ['/workflow-rules/index'],
                        'active' => in_array($controllerId, ['workflow-rules', 'workflow-rule-definitions']),
                        'visible' => in_array('common-models-WorkflowRule', $tenantModules)
                    ],
            ]);
        }

        // 站点管理
        $items['site.management'] = [
            'label' => Yii::t('app', 'Site Management'),
            'url' => []
        ];
        $siteManagementChildren = [];
        $i = 1;
        foreach ($contentModules as $name => $module) {
            if (in_array($name, $tenantModules) && (!isset($module['embedSiteManangement']) || $module['embedSiteManangement'] == true)) {
                $activeControllers[] = $module['id'];
                $siteManagementChildren[] = [
                    'label' => Yii::t('app', $module['label']),
                    'url' => $module['url'],
                    'active' => $controllerId == $module['id'] || (isset($module['activeItems']) && in_array($controllerId, $module['activeItems'])),
                ];
                if ($i == 1) {
                    $items['site.management']['url'] = $module['url'];
                }
                $i++;
            }
        }

        if ($siteManagementChildren) {
            $items['site.management']['active'] = in_array($controllerId, $activeControllers);
            $items['site.management']['items'] = $siteManagementChildren;
        } else {
            unset($items['site.management']);
        }

        return $items;
    }

    public function run()
    {
        return $this->render('_controlPanel', [
                'items' => $this->getItems(),
        ]);
    }

}
