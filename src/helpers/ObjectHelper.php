<?php

/**
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace flipbox\transform\helpers;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 3.0.0
 */
class ObjectHelper
{
    /**
     * @param $object
     * @param array $config
     */
    public static function configure($object, array $config = [])
    {
        foreach ($config as $key => $val) {
            $setter = 'set' . $key;
            if (method_exists($object, $setter)) {
                $object->$setter($val);
            } elseif (property_exists($object, $key)) {
                $object->$key = $val;
            }
        }
    }
}
