<?php

namespace api\modules\v1\swagger;

/**
 * @SWG\Definition(
 *   @SWG\Xml(name="##default")
 * )
 */
class ApiResponse
{
    /**
     * @SWG\Property(format="int32", description = "code of result")
     *
     * @var int
     */
    public $errcode;
    /**
     * @SWG\Property
     *
     * @var string
     */
    public $errmsg;
    /**
     * @SWG\Property(format = "array")
     *
     * @var int
     */
    public $data;
}
