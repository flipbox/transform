<?php

/**
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace Flipbox\Transform\Transformers;

use Flipbox\Transform\Helpers\ObjectHelper;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 3.0.0
 */
abstract class AbstractTransformer
{
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
        return call_user_func_array(
            $transformer,
            array_merge(
                [$data],
                $extra
            )
        );
    }

    /**
     * @param mixed $data
     * @param callable $transformer
     * @param array $extra
     * @return mixed
     */
    protected function collection($data, callable $transformer, array $extra = [])
    {
        $items = [];
        foreach ($data as $item) {
            $items[] = $this->item($item, $transformer, $extra);
        }
        return $items;
    }
}
