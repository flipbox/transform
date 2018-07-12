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
abstract class AbstractResource implements ResourceInterface
{
    /**
     * @var mixed
     */
    protected $data;

    /**
     * @var callable
     */
    protected $transformer;

    /**
     * ArrayItem constructor.
     * @param $data
     * @param callable $transformer
     */
    public function __construct($data, callable $transformer)
    {
        $this->data = $data;
        $this->transformer = $transformer;
    }

    /**
     * @inheritdoc
     */
    abstract public function __invoke(Scope $scope, string $identifier = null, ...$params);
}
