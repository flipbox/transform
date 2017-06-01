<?php

/**
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace Flipbox\Transform\Resources;

use Flipbox\Transform\Helpers\Object as ObjectHelper;
use Flipbox\Transform\ParamBag;
use Flipbox\Transform\Scope;

/**
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
    abstract public function transform(callable $transformer, $data);

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
