<?php

/**
 * @package   Transform
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace flipbox\transform\transformers;

use flipbox\transform\resources\Item as ItemResource;
use flipbox\transform\resources\ResourceInterface;
use flipbox\transform\Scope;

/**
 * @package flipbox\transform\transformers
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Item extends AbstractResourceTransformer
{

    /**
     * @param Scope $scope
     * @return ResourceInterface
     */
    protected function createResource(Scope $scope): ResourceInterface
    {
        return new ItemResource($scope);
    }

}