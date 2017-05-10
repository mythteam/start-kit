<?php

namespace backend\models;

use common\models\WebMaster;
use Yii;
use yii\base\Model;

class UpdateProfileForm extends Model
{
    /**
     * @var string
     */
    public $nickname;
    /**
     * @var string
     */
    public $account;
    /**
     * @var string
     */
    public $password;

    /**
     * @var Webmaster
     */
    protected $_user;

    public function rules()
    {
        return [
            [['nickname', 'password'], 'trim'],
            ['password', 'string', 'min' => 6, 'max' => 11],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->_user = Yii::$app->user->identity;

        $this->account = $this->_user->account;
        $this->nickname = $this->_user->nickname;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'account' => '登录账号',
            'password' => '登录密码',
            'nickname' => '用户昵称',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeHints()
    {
        return [
            'password' => '留空，则密码不会修改',
        ];
    }

    /**
     * @return bool
     */
    public function submit()
    {
        if ($this->password) {
            $this->_user->setPassword($this->password);
        }
        $this->_user->nickname = $this->nickname;

        return $this->_user->save();
    }
}
