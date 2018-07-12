<?php

/**
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace Flipbox\Transform\Resources;

use Flipbox\Transform\Scope;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 3.0.0
 */
class Item extends AbstractResource
{
    /**
     * @inheritdoc
     */
    public function __invoke(Scope $scope, string $identifier = null, ...$params)
    {
        $childScope = $scope->childScope($identifier);

        return $childScope->transform(
            $this->transformer,
            $this->data,
            $params
        );
    }
}
