<?php

/**
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace Flipbox\Transform\Helpers;

use Closure;
use Flipbox\Transform\Transformers\TransformerInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 3.0.0
 */
class TransformerHelper
{
    /**
     * @param $transformer
     * @return bool
     */
    public static function isClosure($transformer): bool
    {
        return is_object($transformer) && $transformer instanceof Closure;
    }

    /**
     * @param $transformer
     * @return bool
     */
    public static function isInvokable($transformer): bool
    {
        return is_object($transformer) &&
            is_callable($transformer) &&
            !$transformer instanceof Closure;
    }

    /**
     * @param $transformer
     * @return bool
     */
    public static function isCallableArray($transformer): bool
    {
        return is_array($transformer) && is_callable($transformer);
    }

    /**
     * @param $transformer
     * @return bool
     *
     * @deprecated Use TransformerHelper::isTransformable()
     */
    public static function isTransformer($transformer): bool
    {
        return static::isClosure($transformer) || static::isInvokable($transformer);
    }

    /**
     * @param $transformer
     * @return bool
     */
    public static function isTransformable($transformer): bool
    {
        return
            static::isClosure($transformer) ||
            static::isInvokable($transformer) ||
            static::isCallableArray($transformer);
    }

    /**
     * @param $transformer
     * @return bool
     */
    public static function isTransformerClass($transformer): bool
    {
        return is_string($transformer) &&
            class_exists($transformer) &&
            (
                method_exists($transformer, '__invoke') ||
                is_callable([$transformer, '__invoke'])
            );
    }

    /**
     * @param $transformer
     * @return null|callable
     */
    public static function resolve($transformer)
    {
        if (is_callable($transformer)) {
            return $transformer;
        }

        if (static::isTransformerClass($transformer)) {
            return new $transformer();
        }

        return null;
    }

    /**
     * @param TransformerInterface $transformer
     * @param string $key
     * @return bool
     */
    public static function inInclude(TransformerInterface $transformer, string $key): bool
    {
        return self::findInArray($transformer->getIncludes(), $key) !== null;
    }

    /**
     * @param array $includes
     * @return array
     */
    public static function normalizeIncludes(array $includes): array
    {
        foreach ($includes as $k => $v) {
            if (is_string($v) && ($pos = strrpos($v, '.')) !== false) {
                $v = [substr($v, 0, $pos) => [substr($v, $pos + 1)]];
            }

            // normalize sub-includes
            $v = is_array($v) ? static::normalizeIncludes($v) : $v;

            if (is_numeric($k)) {
                unset($includes[$k]);

                if (is_array($v)) {
                    $k = key($v);
                    $v = reset($v);
                } else {
                    $k = $v;
                }
            }

            $includes[$k] = $v;

            if (($pos = strrpos($k, '.')) !== false) {
                $includes[substr($k, 0, $pos)] = [substr($k, $pos + 1) => $includes[$k]];
                unset($includes[$k]);
            }
        }

        return $includes;
    }

    /**
     * Retrieves the value of an array element or object property with the given key or property name.
     *
     * @param $array
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    private static function findInArray(array $array, string $key, $default = null)
    {
        if (($pos = strrpos($key, '.')) !== false) {
            $array = self::findInArray($array, substr($key, 0, $pos), $default);
            $key = substr($key, $pos + 1);
        }

        return is_array($array) && array_key_exists($key, $array) ? $array[$key] : $default;
    }
}
