<?php

namespace common\models;

use common\Constants;
use common\models\queries\WebmasterQuery;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%webmaster}}".
 *
 * @property int    $id
 * @property int    $status
 * @property int    $is_super
 * @property int    $registed_at
 * @property int    $logged_at
 * @property string $auth_key
 * @property string $nickname
 * @property string $account
 * @property string $password_hash
 * @property string $password_reset_token
 * @property bool   $isSuper
 *
 * @method void touch(string $attribute)
 */
class WebMaster extends ActiveRecord implements IdentityInterface
{
    //是否为超级管理员
    const SUPER_YES = 1;
    const SUPER_NO  = 0;
    
    /**
     * @var string The plain password of the user input.
     */
    public $password;
    
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
            [
                'password',
                'string',
            ],
            
            [['password_hash', 'password_reset_token'], 'string', 'max' => 100],
            [['account'], 'unique'],
            
            ['is_super', 'default', 'value' => self::SUPER_NO],
            ['status', 'default', 'value' => Constants::STATUS_ENABLED],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            '_time' => [
                'class' => TimestampBehavior::class,
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
            'id' => 'ID',
            'status' => '账号状态',
            'is_super' => '是否为超级管理员',
            'registed_at' => '账号创建于',
            'logged_at' => '最后登录于',
            'nickname' => '昵称',
            'account' => '登录账号',
        
        ];
    }
    
    /**
     * {@inheritdoc}
     *
     * @return WebmasterQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new WebmasterQuery(get_called_class());
    }
    
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => Constants::STATUS_ENABLED]);
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
        $timestamp = (int)end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return;
        }
        
        return static::findOne([
            'password_reset_token' => $token,
            'status' => Constants::STATUS_ENABLED,
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
        return static::findOne(['account' => $account, 'status' => Constants::STATUS_ENABLED]);
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
    
    /**
     * Status dictionary
     *
     * @return array
     */
    public static function statusDict()
    {
        return [
            Constants::STATUS_ENABLED => '启用',
            Constants::STATUS_DISABLED => '禁用',
        ];
    }
    
    /**
     * Get the status Label
     *
     * @return string
     */
    public function getStatusLabel()
    {
        return static::statusDict()[$this->status];
    }
    
    /**
     * @return string
     */
    public function getLoggedAt()
    {
        return Yii::$app->getFormatter()->asDatetime($this->logged_at);
    }
    
    /**
     * If the super administrator.
     *
     * @return bool
     */
    public function getIsSuper()
    {
        return $this->is_super == self::SUPER_YES;
    }
}
