<?php

namespace common\components\beanstalkd;

/**
 * Beanstalkd 全局参数.
 */
final class Vars
{
    //tube for register
    const TB_REGISTER = 'register';
    const TB_DEFAULT = 'default';
    const TB_TEST = 'test_tube';

    const TB_TWEET = 'tweet_%s'; //发布
    const TB_TWEET_DELETE = 'tweet_del_%s'; //删除动态
    const TB_TWEET_SYNC = 'tweet_sync_%s'; //同步动态

    const TB_FRIEND_FIRE = 'friend_fire_%s'; //解除好友
}
