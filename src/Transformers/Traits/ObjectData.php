<?php

/**
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace Flipbox\Transform\Transformers\Traits;

use Flipbox\Transform\Scope;
use Traversable;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait ObjectData
{
    /**
     * @param $data
     * @param Scope $scope
     * @return Traversable
     */
    protected function normalizeData(Traversable $data, Scope $scope): Traversable
    {
        foreach ($data as $key => $val) {
            if (!$scope->includeValue($this, $key)) {
                $data->$key = null;
                continue;
            }
            $data->$key = $scope->parseNestedValue($this, $val, $data, $key);
        }

        return $data;
    }
}
