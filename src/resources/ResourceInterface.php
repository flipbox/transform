<?php

/**
 * @package   Transform
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace flipbox\transform\resources;

use flipbox\transform\transformers\TransformerInterface;

/**
 * @package flipbox\transform\resources
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
interface ResourceInterface
{

    /**
     * @param callable|TransformerInterface $transformer
     * @param $data
     * @return mixed
     */
    public function transform(callable $transformer, $data);

    /**
     * @param callable|TransformerInterface $transformer
     * @param $data
     * @return mixed
     */
    public function __invoke($data, callable $transformer);

}
