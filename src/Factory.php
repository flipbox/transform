<?php

/**
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace Flipbox\Transform;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 3.0.0
 */
class Factory
{
    /**
     * @param callable $transformer
     * @param $data
     * @param array $extra
     * @return array|null
     */
    public static function collection(callable $transformer, $data, array $extra = []): array
    {
        $items = [];
        foreach ($data as $item) {
            $items[] = self::item($transformer, $item, $extra);
        }
        return $items;
    }

    /**
     * @param callable $transformer
     * @param $data
     * @param array $extra
     * @return array|null
     */
    public static function item(callable $transformer, $data, array $extra = [])
    {
        return call_user_func_array(
            $transformer,
            array_merge(
                [$data],
                $extra
            )
        );
    }

    /**
     * @param array $config
     * @return Transform
     */
    public static function transform(array $config = []): Transform
    {
        return new Transform($config);
    }
}
