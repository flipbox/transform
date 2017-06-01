<?php

/**
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace Flipbox\Transform\Transformers;

use Flipbox\Transform\Resources\ResourceInterface;
use Flipbox\Transform\Scope;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
abstract class AbstractResourceTransformer extends AbstractTransformer implements ResourceTransformerInterface
{
    /**
     * @var mixed
     */
    protected $data;

    /**
     * @var callable|TransformerInterface
     */
    protected $transformer;

    /**
     * @param Scope $scope
     * @return ResourceInterface
     */
    abstract protected function createResource(Scope $scope): ResourceInterface;

    /**
     * @param Scope $scope
     * @return mixed
     */
    protected function getData(Scope $scope)
    {
        return $this->data;
    }

    /**
     * @param Scope $scope
     * @param string|null $identifier
     * @return mixed
     */
    public function transform(Scope $scope, string $identifier = null)
    {
        $childScope = $scope->childScope($identifier);

        $resource = $this->createResource(
            $childScope
        );

        return $resource->transform(
            $this->transformer,
            $this->getData(
                $childScope
            )
        );
    }

    /**
     * @param callable $transformer
     * @return $this
     */
    public function setTransformer(callable $transformer)
    {
        $this->transformer = $transformer;
        return $this;
    }

    /**
     * @param mixed $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function __invoke($data, Scope $scope, string $identifier = null)
    {
        return $this->transform($scope, $identifier);
    }
}
