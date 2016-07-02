<?php

namespace api\modules\v1\form;

use common\models\User;
use yii\base\Model;

/**
 * @SWG\Definition(required={"username", "password", "device_id"}, @SWG\Xml(name="LoginForm"))
 */
class LoginForm extends Model
{
    /**
     * @SWG\Property(format = "string")
     *
     * @var string
     */
    public $username;
    /**
     * @SWG\Property(type = "string", format = "password")
     *
     * @var string
     */
    public $password;
    /**
     * 当前登录用户是否为原始网站用户，并且密码验证成功
     *
     * @var bool
     */
    protected $isFallbackUser = false;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'trim'],
            [['username', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validate password
     *
     * @param  string $attribute
     * @param  array $params
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                /** @var \common\models\User $user */
                if ($user && ($fallback = $user->fallbackUser)) {
                    //fallback check
                    if (!($this->isFallbackUser = $fallback->validatePassword($this->password))) {
                        $this->addError($attribute, t('app', 'Incorrect username or password.'));
                    }
                } else {
                    $this->addError($attribute, t('app', 'Incorrect username or password.'));
                }
            } elseif ($user->isDisabled) {
                $this->addError($attribute, t('app', 'The username had been locked!'));
            }
        }
    }

    public function login()
    {
        if (!$this->validate()) {
            return $this;
        }
        $user = $this->getUser();
        $user->generateAccessToken();
        $user->trigger(User::EVENT_LOGIN);
        if ($this->isFallbackUser) {
            $user->setPassword($this->password);
            $user->fallbackUser->delete();
        }
        if (false === $user->update()) {
            return $user;
        }
        return [
            'user_id' => $user->id,
            'access_token' => $user->access_token,
        ];
    }

    private $_user;

    /**
     * Finds user by phone or email.
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByAccount($this->username);
        }

        return $this->_user;
    }

    /**
     * {@inheritdoc}
     */
    public function formName()
    {
        return '';
    }
}
