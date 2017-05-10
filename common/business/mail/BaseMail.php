<?php

namespace common\business\mail;

use common\traits\SleepifyTrait;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Object;
use yii\mail\BaseMailer;

/**
 * Base Class of all mailer logic class.
 *
 * We can send directly by calling `send`, but use redis is better;
 */
abstract class BaseMail extends Object
{
    use SleepifyTrait;

    /**
     * The business logic of send the email.
     *
     * @return bool
     */
    public function send()
    {
        //do not send email, when return false.
        if (false === $this->beforeSend()) {
            return;
        }
        if (null === $this->to) {
            throw new InvalidConfigException('The main email target must be setted.');
        }
        $mailer = Yii::$app->get('mailer');
        $mailer->on(BaseMailer::EVENT_AFTER_SEND, ['common\business\mail\monitor\TrackAfterSend', 'listen']);

        $message = $mailer->compose($this->template, $this->params);

        $message->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
                ->setSubject($this->subject);

        $message->setTo($this->to);
        $this->cc and $message->setCc($this->cc);

        return $message->send();
    }

    /**
     * Preview locally
     * ~~~
     * $notify->render();
     * ~~~.
     *
     * @return mixed
     */
    public function render()
    {
        //do not send email, when return false.
        if (false === $this->beforeSend()) {
            return;
        }
        if (null === $this->to) {
            throw new InvalidConfigException('The main email target must be setted.');
        }
        $mailer = Yii::$app->get('mailer');

        return $mailer->render($this->template, $this->params, '@common/business/mail/views/layouts/transactional.php');
    }

    /**
     * Logic of before send,
     * We should set to or cc ect.
     */
    abstract protected function beforeSend();

    /**
     * @var string|array
     */
    private $_to;

    /**
     * Set mailer target emial.
     *
     * @param string|array $to 'xx@email.com' or ['xx@email.com' => 'name']
     *
     * @return $this
     */
    public function setTo($to)
    {
        $this->_to = $to;

        return $this;
    }

    /**
     * get send to emails.
     *
     * @return array|string
     */
    public function getTo()
    {
        return $this->_to;
    }

    /**
     * @var string|array
     */
    private $_cc;

    /**
     * set mailer cc target email.
     *
     * @param string|array $cc
     *
     * @return $this
     */
    public function setCc($cc)
    {
        $this->_cc = $cc;

        return $this;
    }

    /**
     * get cc email list.
     *
     * @return string|array
     */
    public function getCc()
    {
        return $this->_cc;
    }

    /**
     * get the email template string.
     *
     * @return string
     */
    abstract public function getTemplate();

    private $_params;

    /**
     * get the params set to mail template.
     *
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * Setter of params.
     *
     * @param array $params
     */
    public function setParams($params)
    {
        $this->_params = $params;

        return $this;
    }

    /**
     * Email title.
     *
     * @return string
     */
    abstract public function getSubject();

    /**
     * return the redis queue name
     * We can override this to change the redis list.
     *
     * @return string
     */
    public static function queueName()
    {
        return 'mailers';
    }

    /**
     * Put the mailer task into queue.
     *
     * @return int|null
     */
    public function enqueue()
    {
        $redis = Yii::$app->get('redis', false);
        if (null === $redis) {
            return;
        }

        return $redis->lpush(static::queueName(), serialize($this));
    }

    /**
     * enqueue multiple task at once.
     *
     * @return int
     */
    public static function enqueueMultiple()
    {
        $tasks = func_get_args();
        $tasks = array_map(function ($obj) {
            return serialize($obj);
        }, $tasks);

        $redis = Yii::$app->get('redis', false);
        if (null === $redis) {
            return;
        }
        array_unshift($tasks, static::queueName());

        return $redis->executeCommand('LPUSH', $tasks);
    }

    /**
     * dequeue to get the task.
     *
     * @param object $redis
     *
     * @return string serialized task instanceo
     */
    public static function dequeue($redis = null)
    {
        if (null === $redis) {
            $redis = Yii::$app->get('redis', false);
            if (null === $redis) {
                return;
            }
        }

        return $redis->rpop(static::queueName());
    }
}
