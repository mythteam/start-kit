<?php

namespace api\modules\v1\swagger;

/**
 * @SWG\Definition(required={"url"}, @SWG\Xml(name="Image"))
 */
class Image
{
    /**
     * 上传七牛后的地址
     * @SWG\Property(default = "https://dn-iyuban.qbox.me/img/avatar.png")
     *
     * @var string
     */
    public $url;
}

/**
 * @SWG\Definition(required={"access_token", "content", "images"}, @SWG\Xml(name="Tweet"))
 */
class Tweet
{
    /**
     * @SWG\Property(format = "string")
     *
     * @var string
     */
    public $access_token;

    /**
     * @SWG\Property()
     *
     * @var string
     */
    public $content;
    /**
     * @SWG\Property()
     *
     * @var integer
     */
    public $permission;

    /**
     * @var Image[]
     * @SWG\Property()
     */
    public $images;
}

/**
 * @SWG\Definition(required={"id", "profile"}, @SWG\Xml(name="User"), description = "dsadas")
 */
class User
{
    /**
     * @SWG\Property(
     *     format = "int64",
     *     description = "用户ID"
     * )
     *
     * @var integer
     */
    public $id;

    /**
     * @SWG\Property(@SWG\Schema(
     *            ref = "#/definitions/UserProfile"
     *         ))
     *
     * @var mixed
     */
    public $profile;
}

/**
 * @SWG\Definition(required={"sex"}, @SWG\Xml(name="UserProfile"))
 */
class UserProfile
{
    /**
     * @SWG\Property(format = "int64", enum = {1, 2}, description ="dsadasd")
     *
     * @var integer
     */
    public $sex;
}

/**
 * @SWG\Definition(required={"total"}, @SWG\Xml(name="Balance"))
 */
class Balance
{
    /**
     * @SWG\Property(description = "账户总余额", example = "100.12")
     *
     * @var integer
     */
    public $total;

    /**
     * @SWG\Property(description = "充值总值", example = "55.12")
     *
     * @var integer
     */
    public $recharge;

    /**
     * @SWG\Property(description = "消费总值", example = "100.12")
     *
     * @var integer
     */
    public $consumed;

    /**
     * @SWG\Property(format = "挣得总值", example = "823.12")
     *
     * @var integer
     */
    public $earned;

    /**
     * @SWG\Property(description = "货比码", default = "CNY")
     *
     * @var string
     */
    public $currency;
}

/**
 * @SWG\Definition(required={"id", "status", "statusLabel", "flag", "flagLabel", "flow", "sum", "time", "display_user"}, @SWG\Xml(name="BillHistoryItem"))
 */
class BillHistoryItem
{
    /**
     * 账单ID
     * @SWG\Property(example = 10000)
     *
     * @var integer
     */
    public $id;

    /**
     * 账单状态标志位，不用于显示
     *
     * @SWG\Property(example = 1)
     *
     * @var integer
     */
    public $status;

    /**
     * 账单标志位说明, 用于显示
     *
     * @SWG\Property(example = "交易成功")
     *
     * @var string
     */
    public $statusLabel;

    /**
     * 账单行为标志位
     *
     * @SWG\Property(example = 1)
     *
     * @var integer
     */
    public $flag;
    /**
     * 账单行为标志位说明,用于显示
     *
     * @SWG\Property(example = "充值")
     *
     * @var string
     */
    public $flagLabel;
    /**
     * 金钱流向
     *
     * @SWG\Property()
     *
     * @var integer
     */
    public $flow;
    /**
     * 账单金额
     *
     * @SWG\Property()
     *
     * @var number
     */
    public $sum;
    /**
     * 账单创建时间
     *
     * @SWG\Property(example = "2016-01-19 17:21")
     *
     * @var string
     */
    public $time;

    /**
     * 显示用户信息
     *
     * @SWG\Property()
     *
     * @var SimpleUser
     */
    public $display_user;
}

/**
 * @SWG\Definition(required={"id", "state", "username", "avatar"}, @SWG\Xml(name="SimpleUser"))
 */
class SimpleUser
{
    /**
     * 用户ID
     *
     * @SWG\Property()
     *
     * @var integer
     */
    public $id;
    /**
     * 在线状态, 1表示在线  2.表示忙碌 0 表示离线
     *
     * @SWG\Property(example = 1)
     *
     * @var integer
     */
    public $state;
    /**
     * 用户姓名
     *
     * @SWG\Property()
     *
     * @var string
     */
    public $username;
    /**
     * 头像地址
     *
     * @SWG\Property()
     *
     * @var string
     */
    public $avatar;
}

/**
 * @SWG\Definition(required={"id"}, @SWG\Xml(name="Id"))
 */
class Id
{
    /**
     * 用户ID
     *
     * @SWG\Property(example = 10000)
     *
     * @var integer
     */
    public $id;
}
/**
 * @SWG\Definition(required={"access_token", "username"}, @SWG\Xml(name="UserEasemobIdList"))
 */
class UserEasemobIdList
{
    /**
     * Access Token
     *
     * @SWG\Property()
     *
     * @var string
     */
    public $access_token;

    /**
     * @SWG\Property()
     *
     * @var Id[]
     */
    public $idList;
}

/**
 * @SWG\Definition(required={"test", "username"}, @SWG\Xml(name="LoginForm1"))
 */
class LoginForm1
{
    /**
     * @SWG\Property(
     *     type = "array",
     *     default = { "index", "create" },
     *     @SWG\Items(
     *         type="string",
     *     ),
     * )
     *
     * @var string
     */
    public $test;
    /**
     * @SWG\Property(default = {"name", "light"})
     *
     * @var string[]
     */
    public $username;
}

/**
 * @SWG\Definition(required={"username"}, @SWG\Xml(name="LoginForm2"))
 */
class LoginForm2
{
    /**
     * @SWG\Property(format = "string")
     *
     * @var string
     */
    public $username;
}
