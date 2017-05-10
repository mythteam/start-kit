<?php

namespace common;

/**
 * Constants class act as struct.
 *
 * The best practice is using `Constants` to global defined project contants
 *
 * So you can using like this:
 *
 * ~~~
 * use common\Constants;
 *
 * if (Constants::DELETED_YES) {
 *     //anything
 * }
 * ~~~
 */
class Constants
{
    //状态标识
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    //是否删除标记
    const DELETED_YES = 1;
    const DELETED_NO = 0;

    /**
     * @return array
     */
    public static function statusLabels()
    {
        return [
            static::STATUS_ENABLED => '启用',
            static::STATUS_DISABLED => '禁用',
        ];
    }
}
