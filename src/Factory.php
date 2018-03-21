<?php

/**
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace Flipbox\Transform;

use Flipbox\Transform\Transformers\TransformerInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Factory
{
    /**
     * @param callable|TransformerInterface $transformer
     * @param $data
     * @param array $config
     * @param array $extra
     * @return array|null
     */
    public static function collection(callable $transformer, $data, array $config = [], array $extra = [])
    {
        return self::transform($config)->collection($transformer, $data, $extra);
    }

    /**
     * @param callable|TransformerInterface $transformer
     * @param $data
     * @param array $config
     * @param array $extra
     * @return array|null
     */
    public static function item(callable $transformer, $data, array $config = [], array $extra = [])
    {
        return self::transform($config)->item($transformer, $data, $extra);
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
