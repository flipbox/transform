<?php

/**
 * @package   Transform
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace flipbox\transform\transformers;

/**
 * @package flipbox\transform\transformers
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
interface ResourceTransformerInterface extends TransformerInterface
{

    /**
     * @param $data
     * @return static
     */
    public function setData($data);

}
