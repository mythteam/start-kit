<?php

namespace common\components\beanstalkd;

use Pheanstalk\PheanstalkInterface;

trait Traits
{
    /**
     * 处理新用户注册队列.
     *
     * @param int $user_id
     *
     * @return int The job ID
     */
    final public function register($user_id)
    {
        /* @var Beanstalkd $this */
        $this->setTube(Vars::TB_REGISTER);
        
        return $this->put((string)$user_id);
    }
    
    /**
     * 处理用户发布动态.
     *
     * @param int $tweet_id
     *
     * @return int
     */
    final public function publishTweet($tweet_id)
    {
        /** @var Beanstalkd $this */
        $this->setTube(sprintf(Vars::TB_TWEET, $tweet_id % 5));
        
        return $this->put((string)$tweet_id);
    }
    
    /**
     * 处理用户删除动态事件.
     *
     * @param int    $tweet_id
     *
     * @param string $payload
     *
     * @return int
     */
    final public function deleteTweet($tweet_id, $payload)
    {
        /** @var Beanstalkd $this */
        $this->setTube(sprintf(Vars::TB_TWEET_DELETE, $tweet_id % 5));
        
        return $this->put($payload, PheanstalkInterface::DEFAULT_PRIORITY, 50);
    }
    
    /**
     * @param int $from_user_id
     * @param int $target_user_id
     *
     * @return int
     */
    final public function makeFriends($from_user_id, $target_user_id)
    {
        /** @var Beanstalkd $this */
        $this->setTube(sprintf(Vars::TB_TWEET_SYNC, $from_user_id % 5));
        
        return $this->put(serialize(['from' => $from_user_id, 'target' => $target_user_id]));
    }
    
    /**
     * @param int $from_user_id
     * @param int $target_user_id
     *
     * @return int
     */
    final public function fireFriends($from_user_id, $target_user_id)
    {
        /** @var Beanstalkd $this */
        $this->setTube(sprintf(Vars::TB_FRIEND_FIRE, $from_user_id % 5));
    
        return $this->put(serialize(['from' => $from_user_id, 'target' => $target_user_id]));
    }
}
