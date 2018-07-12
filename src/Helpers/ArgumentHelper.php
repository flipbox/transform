<?php

/**
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace Flipbox\Transform\Helpers;

use Flipbox\Transform\Transformers\TransformerInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 3.0.0
 */
class ArgumentHelper
{
    /**
     * Extracts all of the valid arguments for a provided Closure.
     *
     * @param $transformer
     * @param array $params
     * @return array
     */
    public static function closure(\Closure $transformer, array $params): array
    {
        if (empty($params)) {
            return $params;
        }

        try {
            list($args, $extra) = self::interpretFunction(
                new \ReflectionFunction($transformer),
                $params
            );

            return array_merge(
                $args,
                $extra
            );
        } catch (\ReflectionException $e) {
            // Sorry
        }

        return [];
    }

    /**
     * Extracts all of the valid arguments for a provided callable.
     *
     * @param callable $transformer
     * @param array $params
     * @param string $method
     * @return array
     */
    public static function callable(callable $transformer, array $params, string $method = '__invoke'): array
    {
        if (TransformerHelper::isClosure($transformer)) {
            return static::closure($transformer, $params);
        }

        if (empty($params)) {
            return $params;
        }

        try {
            if (is_array($transformer)) {
                $method = $transformer[1] ?? $method;
                $transformer = $transformer[0] ?? $transformer;
            }

            list($one) = self::interpretFunction(
                new \ReflectionMethod($transformer, $method),
                $params
            );

            return $one;
        } catch (\ReflectionException $e) {
            // Sorry
        }

        return [];
    }

    /**
     * Extracts all of the valid arguments for a provided callable.
     *
     * @param TransformerInterface $transformer
     * @param array $params
     * @return array
     */
    public static function transformer(TransformerInterface $transformer, array $params): array
    {
        if (empty($params)) {
            return $params;
        }

        try {
            list($extra, $variadic) = self::interpretFunction(
                new \ReflectionMethod($transformer, 'transform'),
                $params
            );

            list($args) = self::interpretFunction(
                new \ReflectionMethod($transformer, '__invoke'),
                $params
            );

            return array_merge(
                $args,
                $extra,
                $variadic
            );
        } catch (\ReflectionException $e) {
            // Sorry
        }

        return [];
    }

    /**
     * Merges an indexed array of arguments values with their name.  Note, the orders MUST match.
     *
     * @param callable $transformer
     * @param array $params
     * @param string $method
     * @return array
     * @throws \ReflectionException
     */
    public static function mergeCallable(callable $transformer, array $params, string $method = 'transform'): array
    {
        if (TransformerHelper::isClosure($transformer)) {
            return static::mergeClosure($transformer, $params);
        }

        return self::mergeParameters(
            new \ReflectionMethod($transformer, $method),
            $params
        );
    }

    /**
     * Merges an indexed array of arguments values with their name.  Note, the orders MUST match.
     *
     * @param callable $transformer
     * @param array $params
     * @return array
     * @throws \ReflectionException
     */
    public static function mergeClosure(callable $transformer, array $params): array
    {
        return self::mergeParameters(
            new \ReflectionFunction($transformer),
            $params
        );
    }


    /**
     * @param \ReflectionFunctionAbstract $function
     * @param array $params
     * @return array
     */
    private static function mergeParameters(\ReflectionFunctionAbstract $function, array $params): array
    {
        $args = [];

        foreach ($function->getParameters() as $key => $param) {
            $name = $param->name;
            $args[$name] = $params[$key] ?? null;
        }

        return $args;
    }

    /**
     * @param \ReflectionFunctionAbstract $function
     * @param array $params
     * @param array $ignore
     * @return array
     * @throws \InvalidArgumentException
     */
    private static function interpretFunction(
        \ReflectionFunctionAbstract $function,
        array $params,
        array $ignore = []
    ): array {
        $args = $missing = $variadic = [];
        foreach ($function->getParameters() as $param) {
            $name = $param->name;
            if (true === in_array($name, $ignore, true)) {
                continue;
            }

            if (array_key_exists($name, $params)) {
                $args[$name] = static::argType($param, $params[$name]);
            } elseif ($param->isDefaultValueAvailable()) {
                $args[$name] = $param->getDefaultValue();
            } elseif ($param->isVariadic()) {
                $variadic = array_diff_key(
                    ${$param->name},
                    $args
                );
            } else {
                $missing[] = $name;
            }
        }

        if (!empty($missing)) {
            throw new \InvalidArgumentException(sprintf(
                'Missing required parameters "%s".',
                implode(', ', $missing)
            ));
        }

        return [array_values($args), $variadic];
    }

    /**
     * @param \ReflectionParameter $param
     * @param $value
     * @return mixed
     */
    private static function argType(
        \ReflectionParameter $param,
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

        throw new \InvalidArgumentException(sprintf(
            'Invalid data received for parameter "%s".',
            $param->name
        ));
    }
}
