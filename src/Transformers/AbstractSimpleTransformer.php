<?php

/**
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace Flipbox\Transform\Transformers;

use Flipbox\Transform\Helpers\ObjectHelper;
use Flipbox\Transform\Resources\SimpleCollection;
use Flipbox\Transform\Resources\SimpleItem;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 3.0.0
 */
abstract class AbstractSimpleTransformer
{
    /**
     * @param $data
     * @param string $identifier
     * @return mixed
     */
    abstract public function __invoke($data, string $identifier = null);

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        ObjectHelper::configure($this, $config);
    }

    /**
     * @param mixed $data
     * @param callable $transformer
     * @param array $extra
     * @return mixed
     */
    protected function item($data, callable $transformer, array $extra = [])
    {
        return call_user_func_array(new SimpleItem(), [$data, $transformer, $extra]);
    }

    /**
     * @param mixed $data
     * @param callable $transformer
     * @param array $extra
     * @return mixed
     */
    protected function collection($data, $transformer, array $extra = [])
    {
        return call_user_func_array(new SimpleCollection(), [$data, $transformer, $extra]);
    }
}
