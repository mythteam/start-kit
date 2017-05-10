<?php

namespace common\behaviors;

use Yii;
use yii\base\Behavior;
use yii\web\Application;

/**
 * Local Behavior.
 */
class LocaleBehavior extends Behavior
{
    /**
     * @var string
     */
    public $cookieName = '_locale';
    /**
     * @var bool
     */
    public $enablePreferredLanguage = true;

    /**
     * {@inheritdoc}
     */
    public function events()
    {
        return [
            Application::EVENT_BEFORE_REQUEST => 'beforeRequest',
        ];
    }

    /**
     * Resolve application language.
     *
     * @param \yii\base\Event $event
     */
    public function beforeRequest($event)
    {
        //from cookie
        if (
            Yii::$app->getRequest()->getCookies()->has($this->cookieName)
            && !Yii::$app->session->hasFlash('forceUpdateLocale')
        ) {
            $userLocale = Yii::$app->getRequest()->getCookies()->getValue($this->cookieName);
        } else {
            $userLocale = Yii::$app->language;
            //get from user's profile
            if (false/*!Yii::$app->user->isGuest && Yii::$app->user->identity->userProfile->locale*/) {
                $userLocale = Yii::$app->user->getIdentity()->userProfile->locale;
            } elseif ($this->enablePreferredLanguage) {
                $userLocale = Yii::$app->request->getPreferredLanguage($this->getAvailableLocales());
            }
        }
        Yii::$app->language = $userLocale;
    }

    /**
     * @return array
     */
    protected function getAvailableLocales()
    {
        return array_keys(Yii::$app->params['availableLocales']);
    }
}
