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
    //是否删除标记
    const DELETED_YES = 1;
    const DELETED_NO = 0;
}
