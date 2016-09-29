<?php

namespace backend\components;

use common\models\WebMaster;

/**
 * User extend.
 *
 * @property WebMaster $identity
 */
class User extends \yii\web\User
{
    /**
     * @inheritdoc
     */
    public function can($permissionName, $params = [], $allowCaching = true)
    {
        if ($this->identity->isSuper == WebMaster::SUPER_YES) {
            return true;
        }
        
        return parent::can($permissionName, $params, $allowCaching);
    }
    
    /**
     * @return array
     */
    public function getMenus()
    {
        return [
            [
                'label' => '首页',
                'url' => ['/site/index'],
                'icon' => 'fa-dashboard',
            ],
            [
                'label' => '系统管理',
                'items' => [
                    ['label' => '系统账号管理', 'url' => ['/webmaster/account/list']],
                    //['label' => '角色管理', 'url' => ['/webmaster/rbac/role']],
                    ['label' => '权限管理', 'url' => ['/webmaster/rbac/permissions']],
                    ['label' => 'APP发布管理', 'url' => ['/app/index']],
                ],
                'icon' => 'fa-user',
                //'visible' => $this->can('systemManagement'),
            ],
            [
                'label' => '数据管理',
                'items' => [
                    ['label' => t('app', 'Countries'), 'url' => ['/database/country/index']],
                    ['label' => t('app', 'Regions'), 'url' => ['/database/regions/index']],
                    ['label' => t('app', 'Languages'), 'url' => ['/database/language/index']],
                    ['label' => t('app', 'Course Topics'), 'url' => ['/database/course-topic/index']],
                ],
                'icon' => 'fa-database',
                //'visible' => $this->can('dataManagement'),
            ],
            [
                'label' => '用户管理',
                'items' => [
                    ['label' => '内部用户', 'url' => ['/user/internals/index']],
                    ['label' => '注册用户', 'url' => ['/user/default/index']],
                    ['label' => '语伴老师', 'url' => ['/user/default/teachers']],
                    ['label' => '意向用户', 'url' => ['/user/ex/index', 'tab' => 'wait']],
                ],
                'icon' => 'fa-users',
                //'visible' => $this->can('userManagement'),
            ],
            [
                'label' => '信息管理',
                'icon' => 'fa-bars',
                'items' => [
                    ['label' => '课程管理', 'url' => ['/course/default/index']],
                    ['label' => '举报管理', 'url' => ['/report/index']],
                ],
                //'visible' => $this->can('informationManagement'),
            ],
            [
                'label' => '财务管理',
                'items' => [
                    ['label' => '交易记录', 'url' => ['/finance/trade/list']],
                    ['label' => t('app', 'Withdraw'), 'url' => ['/finance/withdraw/index']],
                ],
                'icon' => 'fa-money',
                //'visible' => $this->can('financeManagement'),
            ],
            [
                'label' => '认证管理',
                'items' => [
                    ['label' => '机构认证', 'url' => ['/authentication/organization/index']],
                    ['label' => '个人认证', 'url' => ['/authentication/personal/index']],
                ],
                'icon' => 'fa-lock',
                //'visible' => $this->can('authManagement'),
            ],
            [
                'label' => '报表中心',
                'items' => [
                    ['label' => '每日注册用户', 'url' => ['/charts/default/register']],
                    ['label' => '语伴圈活跃度', 'url' => ['/charts/default/moments']],
                    //['label' => '财务报表', 'url' => ['/charts/default/finance']],
                ],
                'icon' => 'fa-bar-chart',
                'visible' => true,
            ],
        ];
    }
}

