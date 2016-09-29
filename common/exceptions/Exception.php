<?php

namespace common\exceptions;

/**
 * Represent all exceptions.
 *
 * @since 1.0.0
 */
class Exception extends \Exception
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Exception';
    }
}
