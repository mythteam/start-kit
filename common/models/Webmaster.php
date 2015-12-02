<?php

namespace common\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%webmaster}}".
 *
 * @property int $id
 * @property int $status
 * @property int $is_super
 * @property int $registed_at
 * @property int $logged_at
 * @property string $auth_key
 * @property string $nickname
 * @property string $account
 * @property string $password_hash
 * @property string $password_reset_token
 */
class Webmaster extends \yii\db\ActiveRecord implements IdentityInterface
{
    //账号状态
    const STATUS_ENALBED = 1;
    const STATUS_DISABLED = 0;
    //是否为超级管理员
    const SUPER_YES = 1;
    const SUPER_NO = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%webmaster}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'is_super'], 'integer'],
            [['account'], 'required'],
            [['auth_key'], 'string', 'max' => 32],
            [['nickname', 'account'], 'string', 'max' => 50],
            [['password_hash', 'password_reset_token'], 'string', 'max' => 100],
            [['account'], 'unique'],

            ['is_super', 'default', 'value' => self::SUPER_NO],
            ['status', 'default', 'value' => self::STATUS_ENALBED],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            '_time' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'createdAtAttribute' => 'registed_at',
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('models', 'ID'),
            'status' => Yii::t('models', 'Status'),
            'is_super' => Yii::t('models', 'Is Super'),
            'registed_at' => Yii::t('models', 'Registed At'),
            'logged_at' => Yii::t('models', 'Logged At'),
            'auth_key' => Yii::t('models', 'Auth Key'),
            'nickname' => Yii::t('models', 'Nickname'),
            'account' => Yii::t('models', 'Account'),
            'password_hash' => Yii::t('models', 'Password Hash'),
            'password_reset_token' => Yii::t('models', 'Password Reset Token'),
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @return \common\components\querys\WebmasterQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\components\querys\WebmasterQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ENALBED]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by password reset token.
     *
     * @param string $token password reset token
     *
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ENABLE,
        ]);
    }

    /**
     * 根据account查找用户.
     *
     * @param string $account
     *
     * @return static|null
     */
    public static function findByAccount($account)
    {
        return static::findOne(['account' => $account]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password.
     *
     * @param string $password password to validate
     *
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model.
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key.
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token.
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token.
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
