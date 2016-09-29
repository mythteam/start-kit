<?php

namespace common\exceptions;

use yii\base\Model;

class ActiveRecordException extends SystemException
{
    public function __construct($message, $code = 1, \Exception $previous = null)
    {
        if ($message instanceof Model) {
            if ($message->hasErrors()) {
                $previous = new ModelValidationException($message);
                $message = 'Model validation error';
            } else {
                $message = 'Unknown model error occured.';
            }
        }
        parent::__construct($message, $code, $previous);
    }
}
