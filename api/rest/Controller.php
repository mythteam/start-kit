<?php

namespace api\rest;

use api\rest\auth\QueryParamAuth;
use common\traits\MethodInjectionTrait;
use Yii;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\rest\OptionsAction;
use yii\web\Response;

/**
 * Class Controller
 *
 * @property \common\models\User authedUser
 * @package api\rest
 */
class Controller extends \yii\web\Controller
{
    use MethodInjectionTrait;

    /**
     * @var string|array the configuration for creating the serializer that formats the response data.
     */
    public $serializer = [
        'class' => Serializer::class,
        'collectionEnvelope' => 'items',
    ];

    /**
     * @inheritdoc
     */
    public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'options' => [
                'class' => OptionsAction::class,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    'application/xml' => Response::FORMAT_XML,
                ],
                //第一个为默认语言
                'languages' => [
                    'en-US',
                    'en' => 'en-US',
                    'zh' => 'zh-CN',
                    'zh-Hans' => 'zh-CN',
                    'zh-Hans-CN' => 'zh-CN',
                    'zh-CN',
                    'ko' => 'ko-KR',
                    'ko-KR'
                ],
            ],
            // 'security' =>[
            //     'class' => 'api\rest\auth\SecurityAuth'
            // ],
            // 'verbFilter' => [
            //     'class' => VerbFilter::class,
            //     'actions' => $this->verbs(),
            // ],
            'authenticator' => [
                'class' => QueryParamAuth::class,
                'except' => $this->excepts(),
            ],
            // 'rateLimiter' => [
            //     'class' => RateLimiter::class,
            // ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterAction($action, $result)
    {
        $result = parent::afterAction($action, $result);
        return $this->serializeData($result);
    }

    /**
     * Declares the allowed HTTP verbs.
     * Please refer to [[VerbFilter::actions]] on how to declare the allowed verbs.
     * @return array the allowed HTTP verbs.
     */
    protected function verbs()
    {
        return [];
    }

    /**
     * Declares the actions without authentication.
     * @return array the action IDs.
     */
    protected function excepts()
    {
        return [];
    }

    /**
     * Serializes the specified data.
     * The default implementation will create a serializer based on the configuration given by [[serializer]].
     * It then uses the serializer to serialize the given data.
     * @param mixed $data the data to be serialized
     * @return mixed the serialized data.
     */
    protected function serializeData($data)
    {
        return Yii::createObject($this->serializer)->serialize($data);
    }

    /**
     * Send validation faild response
     *
     * @param  string $msg
     *
     * @return null
     */
    public function sendFaildValidation($msg)
    {
        Yii::$app->response->setStatusCode(422, $msg);
    }

    /**
     * Send error response
     *
     * @param  string $msg
     */
    public function error($msg)
    {
        Yii::$app->response->setStatusCode(500, $msg);
    }
}
