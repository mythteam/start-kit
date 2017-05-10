<?php

namespace backend\components\behaviors;

use yii\base\Behavior;
use yii\web\User;

class AfterLoginBehavior extends Behavior
{
    /**
     * @var int
     */
    public $attribute = 'logged_at';

    /**
     * {@inheritdoc}
     */
    public function events()
    {
        return [
            User::EVENT_AFTER_LOGIN => 'afterLogin',
        ];
    }

    /**
     * @param \yii\web\UserEvent $event
     */
    public function afterLogin($event)
    {
        /** @var \common\models\WebMaster $user */
        $user = $event->identity;

        $user->touch($this->attribute);
        $user->update(false, [$this->attribute]);
    }
}
