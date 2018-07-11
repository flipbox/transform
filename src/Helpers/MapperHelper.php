<?php

/**
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace Flipbox\Transform\Helpers;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 3.0.0
 */
class MapperHelper
{
    /**
     * @param array $array
     * @param array $map
     * @return array
     */
    public static function to(array $array, array $map): array
    {
        return static::replaceKeys($array, $map);
    }

    /**
     * @param array $errors
     * @param array $map
     * @return array
     */
    public static function from(array $errors, array $map): array
    {
        return static::replaceKeys($errors, array_flip($map));
    }

    /**
     * @param array $array
     * @param array $keyMap
     * @return array
     */
    public static function replaceKeys(array $array, array $keyMap): array
    {
        array_walk($keyMap, function ($to, $from) use (&$array) {
            $newKey = array_key_exists($from, $array) ? $to : false;
            if ($newKey !== false) {
                $array[$to] = $array[$from];
                unset($array[$from]);
            }
        });

        return $array;
    }
}
