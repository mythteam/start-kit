<?php

namespace api\components;

use yii\base\BootstrapInterface;
use yii\web\UrlRule;

/**
 * Class Setup.
 * Do application initialize things.
 */
class Setup implements BootstrapInterface
{
    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules([
            [
                'class' => UrlRule::class,
                'pattern' => '',
                'route' => 'site/doc',
            ],
            [
                'class' => UrlRule::class,
                'pattern' => 'site/<action:\w+>',
                'route' => 'site/<action>',
            ],
        ]);
    }
}
