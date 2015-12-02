<?php

namespace common\traits;

use Yii;
use yii\base\InlineAction;
use yii\web\BadRequestHttpException;

/**
 * Replce the controller's bindActionPararms function.
 *
 * It's can make action run with injection.
 *
 * Note: This property maybe landed in 2.0.7, and implemented in the inside functions.
 *
 * @see  https://github.com/yiisoft/yii2/issues/9476
 */
trait MethodInjectionTrait
{
    public function bindActionParams($action, $params)
    {
        if ($action instanceof InlineAction) {
            $method = new \ReflectionMethod($this, $action->actionMethod);
        } else {
            $method = new \ReflectionMethod($action, 'run');
        }
        $args = [];
        $missing = [];
        $actionParams = [];
        foreach ($method->getParameters() as $param) {
            $name = $param->getName();
            if (($class = $param->getClass()) !== null) {
                $className = $class->getName();
                if (Yii::$app->has($name) && ($obj = Yii::$app->get($name)) instanceof $className) {
                    $args[] = $actionParams[$name] = $obj;
                } else {
                    $args[] = $actionParams[$name] = Yii::$container->get($className);
                }
            } elseif (array_key_exists($name, $params)) {
                if ($param->isArray()) {
                    $args[] = $actionParams[$name] = (array) $params[$name];
                } elseif (!is_array($params[$name])) {
                    $args[] = $actionParams[$name] = $params[$name];
                } else {
                    throw new BadRequestHttpException(Yii::t('yii', 'Invalid data received for parameter "{param}".', [
                        'param' => $name,
                    ]));
                }
                unset($params[$name]);
            } elseif ($param->isDefaultValueAvailable()) {
                $args[] = $actionParams[$name] = $param->getDefaultValue();
            } else {
                $missing[] = $name;
            }
        }
        if (!empty($missing)) {
            throw new BadRequestHttpException(Yii::t('yii', 'Missing required parameters: {params}', [
                'params' => implode(', ', $missing),
            ]));
        }
        $this->actionParams = $actionParams;

        return $args;
    }
}
