<?php

namespace common\business\mail;

use common\models\User;
use Yii;

/**
 * Send the email to register successfully user.
 */
class RegisterSuccessNotify extends BaseMail
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var string initialize password
     */
    public $password;

    /**
     * {@inheritdoc}
     */
    protected function beforeSend()
    {
        $this->to = $this->email;
        $this->params = [
            'password' => $this->password,
            'email' => $this->email,
        ];

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'security/registerAccountInfo';
    }

    /**
     * {@inheritdoc}
     */
    public function getSubject()
    {
        return __('app', 'Welcome to {app}', ['app' => Yii::$app->name]);
    }
}
