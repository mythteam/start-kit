<?php

namespace common\components\beanstalkd;

use Pheanstalk\Job;
use Pheanstalk\Pheanstalk;
use Pheanstalk\PheanstalkInterface;
use yii\base\Component;

/**
 * The Beanstalkd client.
 *
 * @property Pheanstalk $client
 *
 * @method void bury(Job $job, $priority = PheanstalkInterface::DEFAULT_PRIORITY)
 * @method $this delete(Job $job)
 * @method $this ignore(string $tube)
 * @method int kick(int $max)
 * @method $this kickJob(Job $job)
 * @method array listTubes()
 * @method array listTubesWatched($askServer = false)
 * @method string listTubeUsed($askServer = false)
 * @method $this pauseTube(string $tube, int $delay)
 * @method $this resumeTube(string $tube)
 * @method Job peek(int $jobId)
 * @method Job peekReady($tube = null)
 * @method Job peekBuried($tube = null)
 * @method $this watch(string $tube)
 * @method $this watchOnly(string $tube)
 * @method false|Job reserve($timeout = null)
 * @method Job reserveFromTube(string $tube, $timeout = null)
 * @method object statsJob($job)
 * @method object statsTube(string $tube)
 * @method object stats()
 * @method $this touch(Job $job)
 * @method void useTube(string $tube)
 * @method void release(Job $job, $priority = PheanstalkInterface::DEFAULT_PRIORITY, $delay = PheanstalkInterface::DEFAULT_DELAY)
 */
class Beanstalkd extends Component
{
    use Traits;

    /**
     * @var string the connection address
     */
    public $address = '127.0.0.1';
    /**
     * @var string the connection port
     */
    public $port = 11300;
    /**
     * @var null|int the connection timeout
     */
    public $timeout;
    /**
     * @var bool if connection persistent
     */
    public $persistent = false;
    /**
     * @var string the tube
     */
    protected $tube = PheanstalkInterface::DEFAULT_TUBE;

    /**
     * @var Pheanstalk
     */
    protected $client;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        $this->client = new Pheanstalk(
            $this->address,
            $this->port,
            $this->timeout,
            $this->persistent
        );
    }

    /**
     * @return Pheanstalk
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * {@inheritdoc}
     */
    final public function __call($name, $params)
    {
        if (method_exists($this->client, $name)) {
            $this->client->useTube($this->tube);

            return call_user_func_array([$this->client, $name], $params);
        }

        return parent::__call($name, $params);
    }

    /**
     * @param string $payload
     * @param int    $priority
     * @param int    $delay
     * @param int    $ttr
     *
     * @return int
     */
    final public function put(
        $payload,
        $priority = PheanstalkInterface::DEFAULT_PRIORITY,
        $delay = PheanstalkInterface::DEFAULT_DELAY,
        $ttr = PheanstalkInterface::DEFAULT_TTR
    ) {
        $this->client->useTube($this->tube);

        return $this->client->put($payload, $priority, $delay, $ttr);
    }

    /**
     * 修改管道.
     *
     * @param string $tube
     */
    final public function setTube($tube)
    {
        $this->tube = $tube;
    }

    /**
     * @return \Pheanstalk\Connection
     */
    final public function getConnection()
    {
        return $this->client->getConnection();
    }

    /**
     * @return false|true
     */
    final public function isAvailable()
    {
        return $this->getConnection()->isServiceListening();
    }
}
