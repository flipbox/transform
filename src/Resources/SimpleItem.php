<?php

/**
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace Flipbox\Transform\Resources;

use Flipbox\Transform\Traits\ExtraParameterTrait;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 3.0.0
 */
class SimpleItem implements ResourceInterface
{
    use ExtraParameterTrait;

    /**
     * @param $data
     * @param callable $transformer
     * @param array $extra
     * @return array|null
     */
    public function __invoke($data, callable $transformer, array $extra = [])
    {
        return $this->transform(
            $transformer,
            $data,
            $extra
        );
    }

    /**
     * @param callable $transformer
     * @param $data
     * @param array $extra
     * @return mixed
     */
    public function transform(callable $transformer, $data, array $extra = [])
    {
        $args = [$data, null];

        if (!empty($extra)) {
            try {
                $args = array_merge(
                    $args,
                    $this->validParams($transformer, $extra)
                );
            } catch (\ReflectionException $e) {
                // Sorry
            }
        }

        return call_user_func_array($transformer, $args);
    }
}
