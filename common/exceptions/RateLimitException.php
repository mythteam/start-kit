<?php

namespace common\exceptions;

use Yii;

/**
 * User's operation is too much, reach the rate limit.
 *
 */
class RateLimitException extends Exception
{
    public function __construct($message = null, $code = 1, \Exception $previous = null)
    {
        $message = Yii::t('app', 'Frequent operation, try again later.');
        parent::__construct($message, $code, $previous);
    }
}

