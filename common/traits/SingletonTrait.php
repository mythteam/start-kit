<?php

namespace  common\traits;

/**
 * Class SingletonTrait
 *
 * @package common\traits
 */
trait SingletonTrait
{
    /**
     * @var mixed The instance of the object
     */
    private static $_instance;

    /**
     * @return SingletonTrait|mixed
     */
    public static function getInstance()
    {
        if (!self::$_instance instanceof self) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    /**
     * Disable clone, serialize and new.
     */
    private function __clone(){}
    private function __sleep(){}
    private function __construct(){}
}