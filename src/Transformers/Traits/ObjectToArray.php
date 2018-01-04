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
trait ObjectToArray
{
    use ArrayData;

    /**
     * @param $data
     * @param Scope $scope
     * @param $identifier
     * @return mixed
     */
    abstract public function transform($data, Scope $scope, $identifier);

    /**
     * @inheritdoc
     */
    public function __invoke($data, Scope $scope, string $identifier = null)
    {
        return $this->normalizeData(
            $this->transform($data, $scope, $identifier),
            $scope
        );
    }
}
