<?php

namespace common\jobs\events;

use Pheanstalk\Job;
use yii\base\Event;

/**
 * Class JobFailedEvent.
 */
final class JobFailedEvent extends Event
{
    /**
     * @var string
     */
    public $tube;
    /**
     * @var Job
     */
    public $job;
    /**
     * @var string
     */
    public $reason;
}
