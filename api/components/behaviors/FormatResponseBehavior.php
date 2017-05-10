<?php

namespace api\components\behaviors;

use yii\base\Behavior;
use yii\web\Response;

/**
 * Format the restful response.
 */
class FormatResponseBehavior extends Behavior
{
    /**
     * {@inheritdoc}
     */
    public function events()
    {
        return [
            Response::EVENT_BEFORE_SEND => 'formatResponse',
        ];
    }

    /**
     * @param \yii\base\Event $event
     */
    public function formatResponse($event)
    {
        $response = $event->sender;
        /** @var \yii\web\HeaderCollection $headers */
        $headers = $response->getHeaders();
        $headers->set('Access-Control-Allow-Origin', '*');
        //format response
        if ('html' !== $response->format) {
            if (!isset($response->data['errcode'])) {
                if ($response->data !== null) {
                    $response->data = [
                        'errcode' => $response->isSuccessful ? 0 : $response->statusCode,
                        'errmsg' => $response->statusText,
                        'data' => $response->data,
                    ];
                } else {
                    $response->data = [
                        'errcode' => $response->isSuccessful ? 0 : $response->statusCode,
                        'errmsg' => $response->statusText,
                    ];
                }
            }
        }
        $response->statusCode = 200;
    }
}
