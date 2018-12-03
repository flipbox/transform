<?php

/**
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace flipbox\transform\traits;

use flipbox\transform\helpers\MapperHelper;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 3.0.0
 */
trait MapperTrait
{
    /**
     * @var array
     */
    private $map;

    /**
     * @param $map
     * @return $this
     */
    public function setMap($map)
    {
        $this->map = $map;
        return $this;
    }

    /**
     * @param array $array
     * @return array
     */
    protected function definedMap(array $array): array
    {
        return [];
    }

    /**
     * @param array $array
     * @return array
     */
    public function getMap(array $array): array
    {
        if (!$this->hasMap()) {
            $this->map = $this->definedMap($array);
        }
        return $this->resolveMap($this->map);
    }

    /**
     * @return bool
     */
    public function hasMap(): bool
    {
        return $this->map !== null;
    }

    /**
     * @param $map
     * @return array
     */
    protected function resolveMap($map): array
    {
        if (is_array($map)) {
            return $map;
        }

        if (is_callable($map)) {
            return $this->resolveMap(call_user_func($map));
        }

        return [$map];
    }

    /**
     * @param array $array
     * @return array
     */
    public function mapTo(array $array): array
    {
        return MapperHelper::to($array, $this->getMap($array));
    }

    /**
     * @param array $array
     * @return array
     */
    public function mapFrom(array $array): array
    {
        return MapperHelper::from($array, $this->getMap($array));
    }
}
