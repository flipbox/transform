<?php

/**
 * @package   Transform
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace flipbox\transform;

use flipbox\transform\resources\Collection;
use flipbox\transform\resources\Item;

/**
 * @package flipbox\transform
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Factory
{

    /**
     * @param array $config
     * @return Collection
     */
    public static function collection(array $config = []): Collection
    {
        return new Collection(
            new Scope(
                new Transform($config)
            )
        );
    }

    /**
     * @param array $config
     * @return Item
     */
    public static function item(array $config = []): Item
    {
        return new Item(
            new Scope(
                new Transform($config)
            )
        );

    }

}
