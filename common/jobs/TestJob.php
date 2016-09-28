<?php

namespace common\jobs;

use common\exceptions\Exception;
use Yii;

final class TestJob extends Job
{
    
    /**
     * The main method to handle logic.
     *
     * @return mixed
     */
    public function run()
    {
        echo $this->job->getData();
    }
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        Yii::$app->db->open();
    }
}
