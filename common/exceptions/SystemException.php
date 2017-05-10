<?php

namespace common\exceptions;

class SystemException extends Exception
{
    public function __construct($message, $code = 1, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'SystemException';
    }
}
