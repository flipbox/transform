<?php

/**
 * @package   Transform
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace flipbox\transform\transformers;

use flipbox\transform\Scope;

/**
 * @package flipbox\transform\transformers
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
interface TransformerInterface
{

    /**
     * @return array
     */
    public function getIncludes(): array;

    /**
     * @param $data
     * @param Scope $scope
     * @param string $identifier
     * @return array|string|null
     */
    public function __invoke($data, Scope $scope, string $identifier = null);

}
