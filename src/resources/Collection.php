<?php

/**
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace flipbox\transform\resources;

use flipbox\transform\Scope;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 3.0.0
 */
class Collection extends AbstractResource
{
    /**
     * @inheritdoc
     */
    public function __invoke(Scope $scope, string $identifier = null, ...$params): array
    {
        $childScope = $scope->childScope($identifier);

        $items = [];

        foreach ($this->data as $data) {
            $items[] = $childScope->transform(
                $this->transformer,
                $data,
                $params
            );
        }

        return $items;
    }
}
