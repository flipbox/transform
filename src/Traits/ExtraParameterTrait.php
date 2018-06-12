<?php

/**
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace Flipbox\Transform\Traits;

use InvalidArgumentException;
use ReflectionMethod;
use ReflectionParameter;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 3.0.0
 */
trait ExtraParameterTrait
{
    /**
     * @param $transformer
     * @param array $params
     * @return array
     * @throws \ReflectionException
     */
    private function validParams($transformer, array $params): array
    {
        if (!is_object($transformer)) {
            return $params;
        }

        $method = new ReflectionMethod($transformer, '__invoke');

        $args = $missing = [];
        foreach ($method->getParameters() as $param) {
            $name = $param->name;
            if (true === in_array($name, ['data', 'scope', 'identifier'], true)) {
                continue;
            }
            if (array_key_exists($name, $params)) {
                $args[] = $this->argType($param, $params[$name]);
            } elseif ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
            } else {
                $missing[] = $name;
            }
        }

        if (!empty($missing)) {
            throw new InvalidArgumentException(sprintf(
                'Missing required parameters "%s".',
                implode(', ', $missing)
            ));
        }

        return $args;
    }

    /**
     * @param ReflectionParameter $param
     * @param $value
     * @return mixed
     */
    private function argType(
        ReflectionParameter $param,
        $value
    ) {
        if (!$param->hasType()) {
            return $value;
        }

        if ($param->isArray()) {
            return (array)$value;
        }

        if ($param->isCallable() && is_callable($value)) {
            return $value;
        }

        if (!is_array($value)) {
            return $value;
        }

        throw new InvalidArgumentException(sprintf(
            'Invalid data received for parameter "%s".',
            $param->name
        ));
    }
}