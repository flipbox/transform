<?php

/**
 * @package   Transform
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace flipbox\transform\transformers;

use flipbox\transform\resources\ResourceInterface;
use flipbox\transform\Scope;

/**
 * @package flipbox\transform\transformers
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
abstract class AbstractResourceTransformer extends AbstractTransformer
{

    /**
     * @var mixed
     */
    public $data;

    /**
     * @var callable|TransformerInterface
     */
    protected $transformer;

    /**
     * @param Scope $scope
     * @return ResourceInterface
     */
    protected abstract function createResource(Scope $scope): ResourceInterface;

    /**
     * @param Scope $scope
     * @param string|null $identifier
     * @return mixed
     */
    public function transform(Scope $scope, string $identifier = null)
    {

        $resource = $this->createResource(
            $scope->childScope($identifier)
        );

        return $resource->transform(
            $this->transformer,
            $this->data
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
     * @inheritdoc
     */
    public function __invoke($data, Scope $scope, string $identifier = null)
    {
        return $this->transform($scope, $identifier);
    }

}