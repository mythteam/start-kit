<?php

namespace api\modules\v1;

use common\service\finder\CourseFinder;
use common\service\finder\PartnerFinder;
use common\service\finder\TweetFinder;
use common\service\finder\NotificationFinder;
use Yii;

/**
 *
 * @package api\modules\v1
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'api\modules\v1\controllers';

    public function init()
    {
        parent::init();

        Yii::$app->getUser()->identityClass = 'api\modules\v1\models\User';

        //finder model classes
        $this->initFinderModelClass();
    }

    /**
     * Init finder model class
     */
    protected function initFinderModelClass()
    {
        $container = Yii::$container;

        $container->set(TweetFinder::class, ['modelClass' => 'api\modules\v1\models\Tweet']);
        $container->set(CourseFinder::class, ['modelClass' => 'api\modules\v1\models\Courses']);
        $container->set(PartnerFinder::class, ['modelClass' => 'api\modules\v1\models\Partner']);
        $container->set(NotificationFinder::class, ['modelClass' => 'api\modules\v1\models\TweetNotification']);
    }
}
