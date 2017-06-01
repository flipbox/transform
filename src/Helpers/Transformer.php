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
 * @since 1.0.0
 */
class Transformer
{
    /**
     * @param $transformer
     * @return bool
     */
    public static function isTransformer($transformer)
    {
        return is_callable($transformer) || $transformer instanceof TransformerInterface;
    }

    /**
     * @param $transformer
     * @return bool
     */
    public static function isTransformerClass($transformer)
    {
        return is_string($transformer) && is_subclass_of($transformer, TransformerInterface::class);
    }
}