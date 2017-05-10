<?php

namespace common\business\mail\console;

use common\business\mail\BaseMail;
use Yii;
use yii\base\InvalidConfigException;
use yii\console\Controller;
use yii\redis\Connection;

/**
 * The mailer task console controller.
 */
class RedisTaskController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public $defaultAction = 'run';

    /**
     * @var string|array|object Connection string or configure class
     */
    public $redis = 'redis';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if (is_string($this->redis)) {
            $this->redis = Yii::$app->get($this->redis, false);
        } elseif (is_array($this->redis)) {
            if (!isset($this->redis['class'])) {
                $this->redis['class'] = Connection::className();
                $this->redis = Yii::createObject($this->redis);
            }
        }
        if (!($this->redis instanceof Connection)) {
            throw new InvalidConfigException('Redis component must be configured correctlly!');
        }
    }

    /**
     * Listen mailer task redis queue.
     *
     * Should install redis components, otherwise, pls use db task runner
     */
    public function actionRun()
    {
        $redis = $this->redis;
        while ($mail = BaseMail::dequeue($redis)) {
            $mail = unserialize($mail);
            $mail->send();
        }
    }

    /**
     * Test for mailer
     * ~~~
     * $ yii mailer/test
     * $ yii mailer
     * ~~~.
     */
    public function actionTest()
    {
        //     $mailer = new \common\business\mail\TestMail(['user_id' => $i]);
        //     // echo serialize($mailer);
        //     echo $mailer->enqueue(), PHP_EOL;
        // }
        $mailer = new \common\business\mail\OrgDeliverStudentNotify([
            'user_id' => 10002,
            'org_id' => 10018,
            'stu_id' => 10116,
        ]);
        $mailer->send();
        // echo serialize($mailer);
    }
}
