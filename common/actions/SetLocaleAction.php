<?php

namespace common\actions;

use Yii;
use yii\base\Action;
use yii\base\InvalidParamException;
use yii\web\Cookie;

/**
 * SetLocaleAction.
 */
class SetLocaleAction extends Action
{
    /**
     * @var array List of available locales
     */
    public $locales;

    /**
     * @var string
     */
    public $localeCookieName = '_locale';

    /**
     * @var int
     */
    public $cookieExpire;

    /**
     * @var string
     */
    public $cookieDomain;

    /**
     * @var \Closure
     */
    public $callback;

    /**
     * @param string $locale
     *
     * @return mixed
     */
    public function run($locale)
    {
        if (!is_array($this->locales)
            || !in_array($locale, $this->locales)) {
            throw new InvalidParamException('Unacceptable locale');
        }

        $cookie = new Cookie([
            'name' => $this->localeCookieName,
            'value' => $locale,
            'expire' => $this->cookieExpire ?: time() + 60 * 60 * 24 * 365,
            'domain' => $this->cookieDomain ?: '',
        ]);

        Yii::$app->response->getCookies()->add($cookie);
        if ($this->callback && $this->callback instanceof \Closure) {
            return call_user_func_array($this->callback, [
                $this,
                $locale,
            ]);
        }

        return Yii::$app->response->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }
}
