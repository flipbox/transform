<?php

/**
 * @package   Transform
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace flipbox\transform\resources;

/**
 * @package flipbox\transform\resources
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Item extends AbstractResource
{

    /**
     * @param callable $transformer
     * @param $data
     * @return array|null
     */
    public function transform(callable $transformer, $data)
    {
        return $this->scope->transform(
            $transformer,
            $data
        );
    }

}
