<?php

/**
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace Flipbox\Transform\Transformers\Traits;

use Flipbox\Transform\Resources\ResourceInterface;
use Flipbox\Transform\Scope;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait ObjectToObject
{
    use ObjectData;

    /**
     * @param \Traversable $data
     * @param $scope
     * @param $identifier
     * @return mixed
     */
    abstract public function transform(\Traversable $data, $scope, $identifier);

    /**
     * @inheritdoc
     */
    public function __invoke($data, Scope $scope, string $identifier = null)
    {
        $data = $this->normalizeData($data, $scope);
        return $this->transform($data, $scope, $identifier);
    }
}
