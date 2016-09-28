<?php

namespace common\jobs;

interface JobInterface
{
    /**
     * The main method to handle logic.
     *
     * @return mixed
     */
    public function run();
}
