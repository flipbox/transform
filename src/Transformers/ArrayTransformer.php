<?php

/**
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace Flipbox\Transform\Transformers;

use Flipbox\Transform\Helpers\ArgumentHelper;
use Flipbox\Transform\Helpers\ObjectHelper;
use Flipbox\Transform\Helpers\TransformerHelper;
use Flipbox\Transform\Resources\Collection;
use Flipbox\Transform\Resources\Item;
use Flipbox\Transform\Scope;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 3.0.0
 */
abstract class ArrayTransformer implements TransformerInterface
{
    /**
     * The normalized includes
     *
     * @var null|array
     */
    private $includes;

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
    protected function defineIncludes(): array
    {
        return [];
    }

    /**
     * Returns an array of normalized includes.  It is recommend
     * @return array
     */
    public function getIncludes(): array
    {
        if ($this->includes === null) {
            $this->includes = TransformerHelper::normalizeIncludes(
                $this->defineIncludes()
            );
        }

        return $this->includes;
    }

    /**
     * @param mixed $data
     * @param callable $transformer
     * @return Item
     */
    protected function item($data, $transformer): Item
    {
        return new Item($data, $transformer);
    }

    /**
     * @param mixed $data
     * @param callable $transformer
     * @return Collection
     */
    protected function collection($data, $transformer): Collection
    {
        return new Collection($data, $transformer);
    }

    /**
     * The $params consist of all the attributes found on the Class::transform() method.
     *
     * @param mixed ...$params
     * @return array
     * @throws \ReflectionException
     */
    public function __invoke(Scope $scope, ...$params): array
    {
        // Construct an associative array
        $args = ArgumentHelper::mergeCallable(
            $this,
            $params
        );

        /** @var array $data */
        $data = call_user_func_array(
            [$this, "transform"],
            $args
        );

        return $scope->prepareData($this, $data, null, $args);
    }
}
