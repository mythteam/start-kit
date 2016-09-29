<?php

namespace common\exceptions;

use yii\base\Model;

class ModelValidationException extends SystemException
{
    public function __construct($message, $code = 422, \Exception $previous = null)
    {
        if ($message instanceof Model) {
            $message = implode(';', $message->getFirstErrors());
        }
        parent::__construct($message, $code, $previous);
    }
}
