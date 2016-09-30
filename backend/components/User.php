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
        ];
    }
}

