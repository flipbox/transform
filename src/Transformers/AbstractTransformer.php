<?php

/**
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace Flipbox\Transform\Transformers;

use Flipbox\Transform\Helpers\Object as ObjectHelper;
use Flipbox\Transform\Scope;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
abstract class AbstractTransformer implements TransformerInterface
{

    /**
     * @var bool
     */
    public $filterData = true;

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

    /**
     * @inheritdoc
     */
    public function __invoke($data, Scope $scope, string $identifier = null)
    {
        $response = $this->transform($data, $scope, $identifier);

        if($this->filterData === true) {
            return $this->filterData($response, $scope);
        }

        return $response;
    }

    /**
     * @param $data
     * @param Scope $scope
     * @param null $default
     * @return array
     */
    protected function filterData($data, Scope $scope, $default = null): array
    {
        // Bail now if empty or not iterable
        if (null === $data || !(is_array($data) || $data instanceof \Traversable)) {
            return $default;
        }

        $includedData = [];

        foreach ($data as $key => $val) {
            if (!$scope->includeValue($this, $key)) {
                continue;
            }
            $includedData[$key] = $scope->parseValue($val, $data, $key);
        }

        // Return only the requested fields
        $includedData = $scope->filterFields($includedData);

        return $includedData;
    }
}
