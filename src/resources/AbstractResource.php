<?php

/**
 * @package   Transform
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace flipbox\transform\resources;

use flipbox\transform\helpers\Object as ObjectHelper;
use flipbox\transform\ParamBag;
use flipbox\transform\Scope;

/**
 * @package flipbox\transform\resources
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
abstract class AbstractResource implements ResourceInterface
{

    /**
     * @var Scope
     */
    protected $scope;

    /**
     * @param callable $transformer
     * @param $data
     * @return null|array
     */
    public abstract function transform(callable $transformer, $data);

    /**
     * @param Scope $scope
     * @param array $config
     */
    public function __construct(Scope $scope, array $config = [])
    {
        $this->scope = $scope;
        ObjectHelper::configure($this, $config);
    }

    /**
     * @return ParamBag
     */
    protected function getParams(): ParamBag
    {
        return $this->scope->getParams();
    }

    /**
     * @param $data
     * @param callable $transformer
     * @return array|null
     */
    public function __invoke($data, callable $transformer)
    {
        return $this->transform(
            $transformer,
            $data
        );
    }

}
