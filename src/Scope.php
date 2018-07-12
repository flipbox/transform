<?php

/**
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace Flipbox\Transform;

use Flipbox\Transform\Helpers\ArgumentHelper;
use Flipbox\Transform\Helpers\TransformerHelper;
use Flipbox\Transform\Resources\ResourceInterface;
use Flipbox\Transform\Transformers\TransformerInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 3.0.0
 */
class Scope
{
    /**
     * @var string
     */
    protected $scopeIdentifier;

    /**
     * @var Transform
     */
    protected $transform;

    /**
     * @var array
     */
    protected $parentScopes = [];

    /**
     * Scope constructor.
     * @param Transform $transform
     * @param string|null $scopeIdentifier
     * @param array $parentScopes
     */
    public function __construct(
        Transform $transform,
        string $scopeIdentifier = null,
        array $parentScopes = []
    ) {
        $this->transform = $transform;
        $this->scopeIdentifier = $scopeIdentifier;
        $this->parentScopes = $parentScopes;
    }

    /**
     * @return Transform
     */
    public function getTransform(): Transform
    {
        return $this->transform;
    }

    /**
     * @param $key
     * @return ParamBag
     */
    public function getParams(string $key = null): ParamBag
    {
        return $this->getTransform()->getParams(
            $this->getIdentifier($key)
        );
    }


    /**
     * Get the unique identifier for this scope.
     *
     * @param string $appendIdentifier
     *
     * @return string
     */
    public function getIdentifier(string $appendIdentifier = null): string
    {
        return implode(
            '.',
            array_filter(array_merge(
                $this->parentScopes,
                [
                    $this->scopeIdentifier,
                    $appendIdentifier
                ]
            ))
        );
    }

    /**
     * Is Requested.
     *
     * Check if - in relation to the current scope - this specific segment is allowed.
     * That means, if a.b.c is requested and the current scope is a.b, then c is allowed. If the current
     * scope is a then c is not allowed, even if it is there and potentially transformable.
     *
     * @param string $checkScopeSegment
     *
     * @return bool Returns the new number of elements in the array.
     */
    public function isRequested($checkScopeSegment): bool
    {
        return in_array(
            $this->scopeString($checkScopeSegment),
            $this->transform->getIncludes()
        );
    }

    /**
     * Is Excluded.
     *
     * Check if - in relation to the current scope - this specific segment should
     * be excluded. That means, if a.b.c is excluded and the current scope is a.b,
     * then c will not be allowed in the transformation whether it appears in
     * the list of default or available, requested includes.
     *
     * @param string $checkScopeSegment
     *
     * @return bool
     */
    protected function isExcluded($checkScopeSegment): bool
    {
        return in_array(
            $this->scopeString($checkScopeSegment),
            $this->transform->getExcludes()
        );
    }

    /**
     * @param callable $transformer
     * @param string $key
     * @return bool
     */
    public function includeValue(callable $transformer, string $key): bool
    {
        // Ignore optional (that have not been explicitly requested)
        if ($transformer instanceof TransformerInterface &&
            TransformerHelper::inInclude($transformer, $key) &&
            !$this->isRequested($key)
        ) {
            return false;
        }

        // Ignore excludes
        if ($this->isExcluded($key)) {
            return false;
        }

        return true;
    }

    /**
     * @param callable $transformer
     * @param mixed $data
     * @param array $extra
     * @return array
     */
    public function transform(callable $transformer, $data, array $extra = []): array
    {
        return (array)$this->prepareValue(
            $transformer,
            null,
            array_merge(
                $extra,
                ['data' => $data]
            )
        );
    }

    /**
     * @param callable $transformer
     * @param array $data
     * @param string|null $key
     * @param array $params
     * @return array
     */
    public function prepareData(callable $transformer, array $data, string $key = null, array $params = []): array
    {
        foreach ($data as $k => $val) {
            $newKey = ($key ? $key . '.' : '') . $k;
            if (!$this->includeValue($transformer, $newKey)) {
                unset($data[$k]);
                continue;
            }

            if (is_callable($val)) {
                $data[$k] = $this->prepareCallable($val, $newKey, $params);
            } elseif (is_array($val)) {
                $data[$k] = $this->prepareData($transformer, $val, $newKey, $params);
            } else {
                $data[$k] = $this->prepareValue($val, $newKey, $params);
            }
        }

        return $this->filterFields($data);
    }

    /**
     * @param $value
     * @param string|null $key
     * @param array $params
     * @return mixed
     */
    protected function prepareValue($value, string $key = null, array $params = [])
    {
        if ($value instanceof ResourceInterface) {
            return $this->prepareResource($value, $key, $params);
        }

        if (is_callable($value)) {
            return $this->prepareCallable($value, $key, $params);
        }

        return $value;
    }

    /**
     * @param ResourceInterface $transformer
     * @param string $key
     * @param array $params
     * @return mixed
     */
    protected function prepareResource(
        ResourceInterface $transformer,
        string $key,
        array $params = []
    ) {
        return call_user_func_array(
            $transformer,
            array_merge(
                [$this, $key],
                $params
            )
        );
    }

    /**
     * @param callable $callable
     * @param string|null $key
     * @param array $params
     * @return mixed
     */
    protected function prepareCallable(
        callable $callable,
        string $key = null,
        array $params = []
    ) {
        if ($callable instanceof TransformerInterface) {
            return $this->prepareTransformer($callable, $key, $params);
        }

        if (TransformerHelper::isClosure($callable)) {
            return $this->prepareClosure($callable, $key, $params);
        }

        $args = ArgumentHelper::callable(
            $callable,
            array_merge(
                $params,
                [
                    'scope' => $this,
                    'identifier' => $key
                ]
            )
        );

        return call_user_func_array(
            $callable,
            $args
        );
    }

    /**
     * @param \Closure $transformer
     * @param string|null $key
     * @param array $extra
     * @return mixed
     */
    protected function prepareClosure(
        \Closure $transformer,
        string $key = null,
        array $extra = []
    ) {
        $args = ArgumentHelper::closure(
            $transformer,
            array_merge(
                $extra,
                [
                    'scope' => $this,
                    'identifier' => $key
                ]
            )
        );

        return call_user_func_array(
            $transformer,
            $args
        );
    }

    /**
     * @param TransformerInterface $transformer
     * @param $data
     * @param string|null $key
     * @param array $extra
     * @return mixed
     */
    protected function prepareTransformer(
        TransformerInterface $transformer,
        string $key = null,
        array $extra = []
    ) {
        $args = ArgumentHelper::transformer(
            $transformer,
            array_merge(
                $extra,
                [
                    'scope' => $this,
                    'identifier' => $key
                ]
            )
        );

        return call_user_func_array(
            $transformer,
            $args
        );
    }

    /**
     * @param string $identifier
     * @return Scope
     */
    public function childScope(string $identifier): Scope
    {
        $parentScopes = $this->parentScopes;
        $parentScopes[] = $this->scopeIdentifier;

        return new static(
            $this->getTransform(),
            $identifier,
            $parentScopes
        );
    }

    /**
     * Check, if this is the root scope.
     *
     * @return bool
     */
    protected function isRootScope(): bool
    {
        return empty($this->parentScopes);
    }

    /**
     * Filter the provided data with the requested filter fields for
     * the scope resource
     *
     * @param array $data
     *
     * @return array
     */
    protected function filterFields(array $data): array
    {
        $fields = $this->getFilterFields();

        if ($fields === null) {
            return $data;
        }

        return array_intersect_key(
            $data,
            array_flip(
                iterator_to_array($fields)
            )
        );
    }

    /**
     * Return the requested filter fields for the scope resource
     *
     * @internal
     *
     * @return ParamBag|null
     */
    protected function getFilterFields()
    {
        return $this->transform->getField(
            $this->scopeIdentifier
        );
    }

    /**
     * @param string $checkScopeSegment
     * @return string
     */
    private function scopeString(string $checkScopeSegment): string
    {
        if (!empty($this->parentScopes)) {
            $scopeArray = array_slice($this->parentScopes, 1);
            array_push($scopeArray, $this->scopeIdentifier, $checkScopeSegment);
        } else {
            $scopeArray = [$checkScopeSegment];
        }

        return implode('.', (array)$scopeArray);
    }
}
