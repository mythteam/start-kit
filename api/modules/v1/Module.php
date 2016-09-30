<?php

namespace api\modules\v1;

use Yii;

/**
 *
 * @package api\modules\v1
 */
final class Module extends \yii\base\Module
{
    public $controllerNamespace = 'api\modules\v1\controllers';
    
    public function init()
    {
        parent::init();
        
        //Yii::$app->getUser()->identityClass = 'api\modules\v1\models\User';
        
        //finder model classes
        //$this->initFinderModelClass();
    }
    
    /**
     * Init finder model class
     */
    protected function initFinderModelClass()
    {
        $container = Yii::$container;
        
        //$container->set(TweetFinder::class, ['modelClass' => 'api\modules\v1\models\Tweet']);
    }
}
