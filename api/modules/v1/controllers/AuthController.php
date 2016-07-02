<?php

namespace api\modules\v1\controllers;

use api\modules\v1\form\LoginForm;
use api\modules\v1\form\RegisterForm;
use api\rest\Controller;
use common\models\User;
use common\modules\sms\validators\PhoneValidator;
use Yii;
use yii\base\DynamicModel;
use yii\web\UnauthorizedHttpException;

/**
 * @SWG\Get(
 *    path = "/auth/sms",
 *    tags = {"sms"},
 *    operationId = "send-sms-token",
 *    summary = "发送注册短信验证码",
 *    description = "根据手机号发送短信验证码, 在DEBUG模式下, **data** 会返回生成的验证码",
 *    produces = {"application/json"},
 *    consumes = {"application/json"},
 *    @SWG\Parameter(
 *        in = "query",
 *        name = "tel",
 *        description = "包含国家区号的手机号, 需要urlencode. eg:+8615210345047",
 *        required = true,
 *        type = "string"
 *    ),
 *    @SWG\Response(response = 200, description = "success")
 * )
 *
 * @SWG\Post(
 *    path = "/auth/sms",
 *    tags = {"sms"},
 *    operationId = "validate-sms-token",
 *    summary = "验证注册短信验证码的正确性",
 *    description = "这里的手机号必须是带上国家码并以`+`开头经过`urlencode`的字符串",
 *    produces = {"application/json"},
 *    consumes = {"application/json"},
 *    @SWG\parameter(
 *        in = "query",
 *        name = "tel",
 *        description = "手机号",
 *        required = true,
 *        type = "string"
 *    ),
 *    @SWG\parameter(
 *        in = "query",
 *        name = "code",
 *        description = "短信验证码",
 *        required = true,
 *        type = "string"
 *    ),
 *    @SWG\Response(response = 200, description = "success")
 * )
 *
 * @SWG\Get(
 *    path = "/auth/reset-sms",
 *    tags = {"sms"},
 *    operationId = "send-sms-token",
 *    summary = "发送找回密码短信验证码",
 *    description = "根据手机号发送短信验证码, 在DEBUG模式下, **data** 会返回生成的验证码. 接口会判断用户输入的手机号是否注册，如果为注册则返回`{'errcode': 422, 'errmsg': '15210345089 has not registed.'}`",
 *    produces = {"application/json"},
 *    consumes = {"application/json"},
 *    @SWG\Parameter(
 *        in = "query",
 *        name = "tel",
 *        description = "包含国家区号的手机号, 需要urlencode. eg:+8615210345047",
 *        required = true,
 *        type = "string"
 *    ),
 *    @SWG\Response(response = 200, description = "success")
 * )
 *
 * @SWG\Post(
 *    path = "/auth/reset-sms",
 *    tags = {"sms"},
 *    operationId = "validateResetPasswordToken",
 *    summary = "验证找回密码短信验证码的正确性",
 *    description = "验证通过后会返回一个`passwordResetToken` **data值,在3600s后过期**，重置密码接口需要携带上",
 *    produces = {"application/json"},
 *    consumes = {"application/json"},
 *    @SWG\parameter(
 *        in = "query",
 *        name = "tel",
 *        description = "包含国家区号的手机号, 需要urlencode. eg:+8615210345047",
 *        required = true,
 *        type = "string"
 *    ),
 *    @SWG\parameter(
 *        in = "query",
 *        name = "code",
 *        description = "短信验证码",
 *        required = true,
 *        type = "string"
 *    ),
 *    @SWG\Response(response = 200, description = "success")
 * )
 *
 */
class AuthController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'send-sms-token' => [
                'class' => 'common\modules\sms\actions\SendAction',
                'phoneCheckCallback' => function ($tel) {
                    if (User::checkPhoneIsRegisted($tel)) {
                        $this->sendFaildValidation(t('app', '{phone} had been registed.', ['phone' => $tel]));
                        return false;
                    }
                },
            ],
            'send-reset-sms-token' => [
                'class' => 'common\modules\sms\actions\SendAction',
                'phoneCheckCallback' => function ($tel) {
                    if (!User::checkPhoneIsRegisted($tel)) {
                        $this->sendFaildValidation(t('app', '{phone} has not registed.', ['phone' => $tel]));
                        return false;
                    }
                },
            ],
            'validate-sms-token' => [
                'class' => 'common\modules\sms\actions\ValidateAction',
            ],
            'validate-reset-sms-token' => [
                'class' => 'common\modules\sms\actions\ValidateAction',
                'validCallback' => function ($phone) {
                    $phone = Yii::$container->get('libphonenumber\PhoneNumberUtil')->parse($phone, null)->getNationalNumber();
                    /** @var \common\models\User $user */
                    $user = User::findByPhone($phone);
                    if (null === $user) {
                        return $this->sendFaildValidation(t('app', '{phone} has not registed.', ['phone' => $phone]));
                    }
                    $user->generatePasswordResetToken();
                    $user->update();
                    return $user->password_reset_token;
                },
            ],
        ];
    }

    /**
     * @SWG\Post(
     *    path = "/auth/login",
     *    tags = {"authentication"},
     *    operationId = "userLogin",
     *    summary = "用户登录",
     *    produces = {"application/json"},
     *    consumes = {"application/json"},
     *    @SWG\Parameter(
     *        in = "formData",
     *        name = "username",
     *        description = "`username`即可为手机号也可以是电子邮箱",
     *        required = true,
     *        type = "string"
     *    ),
     *    @SWG\parameter(
     *        in = "formData",
     *        name = "password",
     *        description = "登录密码",
     *        required = true,
     *        type = "string",
     *        format = "password"
     *    ),
     *    @SWG\Response(response = 200, description = "Login Success")
     * )
     * @param LoginForm $form
     *
     * @return $this|array
     */
    public function actionLogin(LoginForm $form)
    {
        $form->load(Yii::$app->request->post());

        return $form->login();
    }

    /**
     * @SWG\Post(
     *    path = "/auth/register",
     *    tags = {"authentication"},
     *    operationId = "userRegister",
     *    summary = "用户注册",
     *    description="手机号需要拼接成国家码+手机号的形式",
     *    produces = {"application/json"},
     *    consumes = {"application/json"},
     *    @SWG\parameter(
     *        in = "formData",
     *        name = "username",
     *        description = "用户昵称",
     *        required = true,
     *        type = "string"
     *    ),
     *    @SWG\parameter(
     *        in = "formData",
     *        name = "sex",
     *        description = "性别, **1. 男 2. 女**",
     *        required = true,
     *        type = "integer",
     *        enum = {1, 2},
     *        default = 1,
     *    ),
     *    @SWG\parameter(
     *        in = "formData",
     *        name = "password",
     *        description = "密码",
     *        required = true,
     *        type = "string",
     *        format = "password"
     *    ),
     *    @SWG\parameter(
     *        in = "formData",
     *        name = "mother_tongue",
     *        description = "母语ID",
     *        required = true,
     *        type = "integer"
     *    ),
     *    @SWG\parameter(
     *        in = "formData",
     *        name = "avatar",
     *        description = "已上传的头像链接地址,使用七牛链接地址 `https://dn-iyuban.qbox.me/img/avatar.png`, 其他地址无效",
     *        required = true,
     *        type = "string"
     *    ),
     *    @SWG\parameter(
     *        in = "formData",
     *        name = "phone",
     *        description = "包含国家区号的手机号, 需要urlencode. eg:+8615210345047",
     *        required = true,
     *        type = "string"
     *    ),
     *    @SWG\parameter(
     *        in = "formData",
     *        name = "country_id",
     *        description = "用户选择的国家ID, 如`10042`代表中国",
     *        required = true,
     *        type = "integer"
     *    ),
     *    @SWG\Response(response = 200, description = "success")
     * )
     * @param RegisterForm $form
     *
     * @return $this|array|User|\common\models\UserProfile
     * @throws \common\exceptions\SystemException
     */
    public function actionRegister(RegisterForm $form)
    {
        $form->load(app()->request->post());
        return $form->register();
    }

    /**
     * 重置密码
     *
     * @SWG\Post(
     *    path = "/auth/reset-pwd",
     *    tags = {"authentication"},
     *    operationId = "reset-password",
     *    summary = "用户通过注册手机找回密码",
     *    description = "此接口用户在用户**未登录状态**下通过手机进行密码重置。",
     *    produces = {"application/json"},
     *    consumes = {"application/json"},
     *    @SWG\parameter(
     *        in = "formData",
     *        name = "password",
     *        description = "新密码",
     *        required = false,
     *        type = "string",
     *        format = "password"
     *    ),
     *    @SWG\parameter(
     *        in = "formData",
     *        name = "phone",
     *        description = "包含国家区号的手机号, 需要urlencode. eg:+8615210345047",
     *        required = true,
     *        type = "string"
     *    ),
     *    @SWG\Response(response = 200, description = "success")
     * )
     *
     *
     * @return null
     */
    public function actionResetPassword()
    {
        $model = new DynamicModel(request()->post());

        $model->addRule('phone', 'common\modules\sms\validators\PhoneValidator')
              ->addRule('password', 'common\components\validators\PasswordValidator');

        if (!$model->validate()) {
            return $model;
        }

        /** @var \common\modules\sms\validators\PhoneValidator $phoneValidator */
        $phoneValidator = $model->getActiveValidators('phone')[0];

        $phone = $phoneValidator->getParsedPhone()->getNationalNumber();

        /** @var \common\models\User $user */
        $user = User::findByPhone($phone);
        if (null === $user) {
            return $this->sendFaildValidation(t('app', 'Invalid phone number.'));
        }

        $user->setPassword($model->password);
        $user->update();

        return;
    }

    /**
     * 用户鉴权接口
     *
     * @SWG\Post(
     *    path = "/auth/query",
     *    tags = {"authentication"},
     *    operationId = "authenticateUser",
     *    summary = "对应用户进行鉴权",
     *    description = "`errcode = 401` 代表鉴权失败，需要重新登录. 请求此接口如果鉴权成功则刷新用户的状态为__在线__",
     *    produces = {"application/json"},
     *    consumes = {"application/json"},
     *    @SWG\parameter(
     *        in = "formData",
     *        name = "access_token",
     *        description = "登录返回的token值",
     *        required = true,
     *        type = "string"
     *    ),
     *    @SWG\Response(response = 200, description = "success")
     * )
     * @return mixed
     * @throws UnauthorizedHttpException
     */
    public function actionQuery()
    {
        /** @var \common\models\User $user */
        $user = User::findIdentityByAccessToken(request()->post('access_token'));
        if (null === $user
            || $user->getIsDisabled()
        ) {
            throw new UnauthorizedHttpException(t('yii', 'Login Required'));
        }

        //更新在线状态
        $user->setOnline();
        return;
    }

    /**
     * 验证手机号是否注册
     *
     * @SWG\Post(
     *    path = "/auth/phone",
     *    tags = {"authentication"},
     *    operationId = "authPhone",
     *    summary = "验证手机号是否注册",
     *    description = "不需要进行鉴权, *data* 为`false`表示已经注册用户 `true`表示可以使用，尚未注册用户",
     *    produces = {"application/json"},
     *    consumes = {"application/json"},
     *    @SWG\Parameter(
     *        in = "formData",
     *        name = "phone",
     *        description = "包含国家区号的手机号, 需要urlencode. eg:+8615210345047",
     *        required = true,
     *        type = "string"
     *    ),
     *    @SWG\Response(response = 200, description = "success")
     * )
     *
     * @return mixed
     */
    public function actionPhone()
    {
        $validator = new PhoneValidator();
        $validator->validate(request()->post('phone'), $err);
        if ($err) {
            return $this->sendFaildValidation($err);
        }
        $phone = $validator->getParsedPhone()->getNationalNumber();

        $user = User::findByPhone($phone);

        return [
            'errcode' => 0,
            'errmsg' => 'Ok',
            'data' => $user === null,
        ];
    }
}
