<?php

/**
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace Flipbox\Transform\Transformers;

use Flipbox\Transform\Helpers\ObjectHelper;
use Flipbox\Transform\Scope;
use Flipbox\Transform\Transformers\Traits\ArrayToObject;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
abstract class AbstractTransformer implements TransformerInterface
{
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
}
