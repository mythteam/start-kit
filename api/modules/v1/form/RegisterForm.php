<?php

namespace api\modules\v1\form;

use common\components\validators\PasswordValidator;
use common\components\validators\UsernameValidator;
use common\Constants;
use common\exceptions\SystemException;
use common\models\User;
use common\models\UserProfile;
use libphonenumber\PhoneNumberFormat;
use light\Easemob\Easemob;
use light\Easemob\Exception\EasemobException;
use Yii;
use yii\base\Model;

/**
 * @SWG\Definition(required={"phone", "password", "username"}, @SWG\Xml(name="RegisterForm"))
 */
class RegisterForm extends Model
{
    /**
     * @SWG\Property(format = "string")
     *
     * @var string
     */
    public $username;

    /**
     * @SWG\Property(format = "int32", enum = {1, 2})
     *
     * @var int
     */
    public $sex;

    /**
     * @SWG\Property(format = "password", type = "string")
     *
     * @var string
     */
    public $password;

    /**
     * @SWG\Property(format = "int64")
     *
     * @var int
     */
    public $mother_tongue;

    /**
     * @SWG\Property(format = "string")
     *
     * @var string
     */
    public $avatar;

    /**
     * @SWG\Property(format = "int32")
     *
     * @var string
     */
    public $phone;

    /**
     * @SWG\Property(format = "int64")
     *
     * @var int
     */
    public $country_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password', 'phone'], 'trim'],
            [['username', 'password', 'country_id', 'phone', 'sex', 'avatar', 'mother_tongue'], 'required'],
            [
                'username',
                UsernameValidator::class,
            ],
            [['mother_tongue', 'country_id'], 'integer'],
            [
                'password',
                PasswordValidator::class,
            ],
            ['avatar', 'common\components\validators\UploadedAvatarValidator'],
            // ['sex', 'default', 'value' => 0],
            ['sex', 'common\components\validators\SexValidator'],
            //double check phone
            ['phone', 'common\modules\sms\validators\PhoneValidator'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function formName()
    {
        return '';
    }

    /**
     * @return $this|array|User|UserProfile
     *
     * @throws SystemException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function register()
    {
        if (!$this->validate()) {
            return $this;
        }

        /** @var \libphonenumber\PhoneNumberUtil $phoneUtil */
        $phoneUtil = Yii::$container->get('libphonenumber\PhoneNumberUtil');
        /** @var \libphonenumber\PhoneNumber $phoneNumber */
        $phoneNumber = $phoneUtil->parse($this->phone, null);

        //有些国家，例如:KR, 默认给去掉了国家前缀0
        //https://github.com/giggsey/libphonenumber-for-php/issues/111#issuecomment-217113207
        $nationalNumber = $phoneUtil->format($phoneNumber, PhoneNumberFormat::NATIONAL);
        $nationalNumber = preg_replace('[-|\s|\(|\)]', '', $nationalNumber);

        if ($nationalNumber !== $phoneNumber->getNationalNumber()) {
            $phoneNumber->setNationalNumber($nationalNumber);
        }
        unset($phoneUtil, $nationalNumber);

        /** @var \light\Easemob\Easemob $easemob */
        $easemob = Yii::createObject(Easemob::class);

        /** @var \light\Easemob\Rest\User $easemobUserComponent */
        $easemobUserComponent = $easemob->user;

        $user = new User();
        $user->phone = $phoneNumber->getNationalNumber();
        $user->calling_code = (string) $phoneNumber->getCountryCode();
        $user->setPassword($this->password);
        $user->generateAccessToken();
        $user->nickname = $this->username;

        $profile = new UserProfile();
        $profile->sex = $this->sex;
        $profile->avatar = $this->avatar;
        $profile->from_country = $this->country_id;

        //母语
        $user->mother_tongue = $profile->mother_tongue = $this->mother_tongue;

        /** @var \yii\db\Transaction $transaction */
        $transaction = app('db')->beginTransaction();
        try {
            if (false === $user->insert()) {
                $transaction->rollBack();

                return $user;
            }

            $profile->user_id = $user->id;
            if (is_null($profile->can_lang)) {
                $profile->can_lang = '';
            }
            if (is_null($profile->learn_lang)) {
                $profile->learn_lang = '';
            }
            if (false === $profile->insert()) {
                $transaction->rollBack();

                return $profile;
            }
            //register user to easemob
            $data = [
                'username' => $user->id,
                'password' => md5($user->id . Constants::EASEMOB_SALT),
            ];
            //register the easemob sync
            $result = $easemobUserComponent->register($data);
            if (false === $result) {
                $transaction->rollBack();

                return [
                    'errcode' => 1,
                    'errmsg' => 'Register Error',
                ];
            }
            $transaction->commit();

            return [
                'user_id' => $user->id,
                'access_token' => $user->access_token,
            ];
        } catch (EasemobException $e) {
            $transaction->rollBack();
            throw new SystemException($e->getMessage(), 1, $e);
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new SystemException($e->getMessage(), 1, $e);
        }
    }
}
