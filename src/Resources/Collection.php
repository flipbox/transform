<?php

/**
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace Flipbox\Transform\Resources;

use ArrayIterator;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property array|ArrayIterator $data
 */
class Collection extends Item
{
    /**
     * @param callable $transformer
     * @param $data
     * @param array $extra
     * @return array|null
     */
    public function transform(callable $transformer, $data, array $extra = [])
    {
        $items = [];

        foreach ($data as $item) {
            $items[] = parent::transform(
                $transformer,
                $item,
                $extra
            );
        }

        return $items;
    }
}
