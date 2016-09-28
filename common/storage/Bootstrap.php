<?php

namespace common\modules\storage;

use FileNamingResolver\FileNamingResolver;
use FileNamingResolver\NamingStrategy\AggregateNamingStrategy;
use FileNamingResolver\NamingStrategy\DatetimeNamingStrategy;
use FileNamingResolver\NamingStrategy\HashNamingStrategy;
use Qiniu\Auth;
use Qiniu\Config;
use Qiniu\Storage\UploadManager;
use Qiniu\Zone;
use Yaconf;
use Yii;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        $this->_initQiniu();
    }

    /**
     * 初始化七牛操作类.
     */
    private function _initQiniu()
    {
        //Qiniu auth object
        Yii::$container->setSingleton(Auth::class, [], [
            Yaconf::get('welfare.qn.access_token'),
            Yaconf::get('welfare.qn.security_token')
        ]);
        //Yii::$container->setSingleton(Config::class, [], [Zone::zone1()]);
        Yii::$container->setSingleton(UploadManager::class, [], [new Config(Zone::zone1())]);
        //file name resolver
        Yii::$container->setSingleton(FileNamingResolver::class, function () {

            $strategy = new AggregateNamingStrategy([
                new DatetimeNamingStrategy(),
                new HashNamingStrategy(HashNamingStrategy::ALGORITHM_MD5, 0),
            ]);

            return new FileNamingResolver($strategy);
        });
    }
}
