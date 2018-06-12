<?php

/**
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace Flipbox\Transform\Transformers\Traits;

use Flipbox\Transform\Scope;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait ArrayData
{
    /**
     * @param $data
     * @param Scope $scope
     * @return array
     */
    protected function normalizeData(array $data, Scope $scope)
    {
        $includedData = [];

        // Bail now
        if (null === $data) {
            return $includedData;
        }

        if (is_string($data)) {
            $data = [$data];
        }

        foreach ($data as $key => $val) {
            if (!$scope->includeValue($this, $key)) {
                continue;
            }
            $includedData[$key] = $scope->parseNestedValue($this, $val, $data, $key);
        }

        // Return only the requested fields
        $includedData = $scope->filterFields($includedData);

        return $includedData;
    }
}
