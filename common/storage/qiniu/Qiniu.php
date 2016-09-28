<?php

namespace common\modules\storage\qiniu;

use Qiniu\Http\Error;
use Qiniu\Storage\BucketManager;
use Yii;

/**
 * 七牛存储操作类
 */
final class Qiniu
{
    const IMG_DEL_SET_KEY = 'qnimg:delete';

    /**
     * Push the needed keys to redis set
     *
     * @param  array $keys
     *
     * @return int The count of added items
     * @throws \yii\base\InvalidConfigException
     */
    public static function deleteJobPush(array $keys)
    {
        $redis = Yii::$app->get('redis');

        array_unshift($keys, self::IMG_DEL_SET_KEY);

        return $redis->executeCommand('SADD', $keys);
    }

    /**
     * 处理图片删除逻辑
     *
     * @return mixed
     */
    public static function consumeDeleteImageJob()
    {
        $redis = Yii::$app->get('redis');
        /** @var \Qiniu\Storage\BucketManager $manager */
        $manager = Yii::createObject(BucketManager::class);

        $start = 0;
        $err_msg = [];

        do {
            list($start, $items) = $redis->executeCommand('sscan', [self::IMG_DEL_SET_KEY, $start]);

            //10 item per request
            $op = [];
            $_delete_keys = [];
            foreach ($items as $key) {
                $op[] = '/delete/' . \Qiniu\entry(static::imageBucketName(), $key);
                $_delete_keys[] = $key;
            }
            if (empty($op)) {
                //no resource to delete, stop the scan and exit.
                break;
            }
            echo 'Batch delete keys:', PHP_EOL;
            print_r($_delete_keys);
            $result = $manager->batch($op);

            foreach ($result as $idx => $_pice) {
                if ($_pice instanceof Error) {
                    $err_msg[] = $_pice->message();
                    continue;
                }
                if (isset($_pice['data'])) {
                    $err_msg[] = "Index: {$idx}";
                    $err_msg[] = $_pice['data']['error'];
                    $err_msg[] = $op;
                }
            }

        } while ($start);

        //we deleted the keys if done
        $redis->del(self::IMG_DEL_SET_KEY);

        //track the error msg
        if ($err_msg) {
            Yii::error($err_msg, 'Qiniu');
        }
    }

    /**
     * Return the image bucket name
     *
     * @return string
     */
    public static function imageBucketName()
    {
        return \Yaconf::get('welfare.qn.bucket_img');
    }
}
