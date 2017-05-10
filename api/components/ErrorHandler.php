<?php

namespace api\components;

use Yii;
use yii\base\UserException;
use yii\web\HttpException;
use yii\web\Response;

/**
 * Class ErrorHandler.
 */
final class ErrorHandler extends \yii\base\ErrorHandler
{
    /**
     * {@inheritdoc}
     */
    protected function renderException($exception)
    {
        if (Yii::$app->has('response')) {
            $response = Yii::$app->getResponse();
            // reset parameters of response to avoid interference with partially created response data
            // in case the error occurred while sending the response.
            $response->isSent = false;
            $response->stream = null;
            $response->data = null;
            $response->content = null;
        } else {
            $response = new Response();
        }

        //force return json format exception
        $response->format = Response::FORMAT_JSON;
        // if ($exception instanceof SystemException) {

        //     $response->send();
        // }
        $response->data = $this->convertExceptionToArray($exception);

        if ($exception instanceof HttpException) {
            $response->setStatusCode($exception->statusCode);
        } else {
            $response->setStatusCode(500);
        }

        $response->send();
    }

    /**
     * {@inheritdoc}
     */
    protected function convertExceptionToArray($exception)
    {
        if (!YII_DEBUG && !$exception instanceof UserException && !$exception instanceof HttpException) {
            $exception = new HttpException(500, 'There was an error at the server.');
        }

        $array = [
            // 'name' => $exception->getName() ?: 'Exception',
            'errmsg' => $exception->getMessage(),
            'errcode' => $exception->getCode() ?: 0,
        ];
        if ($exception instanceof HttpException) {
            $array['errcode'] = $exception->statusCode;
        }
        if (YII_DEBUG) {
            $array['data']['type'] = get_class($exception);
            if (!$exception instanceof UserException) {
                $array['data']['file'] = $exception->getFile();
                $array['data']['line'] = $exception->getLine();
                $array['data']['stack-trace'] = explode("\n", $exception->getTraceAsString());
                if ($exception instanceof \yii\db\Exception) {
                    $array['data']['error-info'] = $exception->errorInfo;
                }
            }
            if (($prev = $exception->getPrevious()) !== null) {
                $array['data']['previous'] = $this->convertExceptionToArray($prev);
            }
        }

        return $array;
    }
}
