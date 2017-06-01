<?php

/**
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace Flipbox\Transform\Transformers;

use Flipbox\Transform\Resources\Collection as CollectionResource;
use Flipbox\Transform\Resources\ResourceInterface;
use Flipbox\Transform\Scope;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Collection extends AbstractResourceTransformer
{
    /**
     * @param Scope $scope
     * @return ResourceInterface
     */
    protected function createResource(Scope $scope): ResourceInterface
    {
        return new CollectionResource($scope);
    }
}