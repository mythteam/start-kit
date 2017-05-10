<?php

namespace api\rest\auth;

use yii\filters\auth\AuthMethod;

class QueryParamAuth extends AuthMethod
{
    /**
     * @var string the parameter name for passing the access token
     */
    public $tokenParam = 'access_token';

    /**
     * @var \common\models\User
     */
    public $authedUser;

    /**
     * {@inheritdoc}
     */
    public function authenticate($user, $request, $response)
    {
        $accessToken = $request->get($this->tokenParam);
        if (is_string($accessToken)) {
            $identity = $user->loginByAccessToken($accessToken, get_class($this));
            if ($identity !== null) {
                return $this->authedUser = $identity;
            }
        }
        if ($accessToken !== null) {
            $this->handleFailure($response);
        }

        return null;
    }
}
