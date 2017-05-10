<?php

namespace common\jobs;

abstract class Job implements JobInterface
{
    /**
     * @var \Pheanstalk\Job
     */
    protected $job;

    public function __construct(\Pheanstalk\Job $job)
    {
        $this->job = $job;

        $this->init();
    }

    /**
     * Do init things.
     */
    public function init()
    {
    }
}
