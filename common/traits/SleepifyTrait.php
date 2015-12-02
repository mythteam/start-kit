<?php

namespace common\traits;

use ReflactionProperty;
use ReflectionClass;

/**
 * Make the class sleepable via serialize etc.
 */
trait SleepifyTrait
{
    /**
     * Magic method of sleep.
     */
    public function __sleep()
    {
        $properties = (new ReflectionClass($this))->getProperties();

        return array_map(function ($property) {
            return $property->getName();
        }, $properties);
    }
    /**
     * Magic method of wake up.
     */
    public function __wakeup()
    {
        $properties = (new ReflectionClass($this))->getProperties();
        foreach ($properties as $property) {
            $property->setValue(
                $this,
                $this->getPropertyValue($property)
            );
        }
    }
    /**
     * Get the property value for the given property.
     *
     * @param ReflactionProperty $property
     *
     * @return mixed
     */
    protected function getPropertyValue(\ReflectionProperty $property)
    {
        $property->setAccessible(true);

        return $property->getValue($this);
    }
}
