<?php

/**
 * @package   Transform
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace flipbox\transform\transformers;

use flipbox\transform\helpers\Object as ObjectHelper;
use flipbox\transform\Scope;

/**
 * @package flipbox\transform\transformers
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
abstract class AbstractTransformer implements TransformerInterface
{

    /**
     * @var Scope
     */
    protected $scope;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        ObjectHelper::configure($this, $config);
    }

    /**
     * @return array
     */
    public function getIncludes(): array
    {
        return [];
    }

    /**
     * @param null $key
     * @return \flipbox\transform\ParamBag|null
     */
    protected function getParams($key = null)
    {
        return $this->scope->getParams($key);
    }

    /**
     * @param mixed $data
     * @param TransformerInterface|callable $transformer
     * @return Item
     */
    protected function item($data, $transformer): Item
    {
        return new Item(['data' => $data, 'transformer' => $transformer]);
    }

    /**
     * @param mixed $data
     * @param TransformerInterface|callable $transformer
     * @return Collection
     */
    protected function collection($data, $transformer): Collection
    {
        return new Collection(['data' => $data, 'transformer' => $transformer]);
    }

    /**
     * @inheritdoc
     */
    public function __invoke($data, Scope $scope, string $identifier = null)
    {
        $this->scope = $scope;
        return $this->transform($data, $identifier);
    }

}
