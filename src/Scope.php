<?php

/**
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace Flipbox\Transform;

use Flipbox\Transform\Helpers\TransformerHelper;
use Flipbox\Transform\Transformers\TransformerInterface;
use InvalidArgumentException;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
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
     * Get the current identifier.
     *
     * @return string|null
     */
    public function getScopeIdentifier()
    {
        return $this->scopeIdentifier;
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
     * Getter for parentScopes.
     *
     * @return array
     */
    public function getParentScopes(): array
    {
        return $this->parentScopes;
    }

    /**
     * Is Requested.
     *
     * Check if - in relation to the current scope - this specific segment is allowed.
     * That means, if a.b.c is requested and the current scope is a.b, then c is allowed. If the current
     * scope is a then c is not allowed, even if it is there and potentially transformable.
     *
     * @internal
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
     * @internal
     *
     * @param string $checkScopeSegment
     *
     * @return bool
     */
    public function isExcluded($checkScopeSegment): bool
    {
        return in_array(
            $this->scopeString($checkScopeSegment),
            $this->transform->getExcludes()
        );
    }

    /**
     * @param TransformerInterface|callable $transformer
     * @param mixed $data
     * @param array $extra
     * @return mixed
     */
    public function transform(callable $transformer, $data, array $extra = [])
    {
        return $this->parseValue($transformer, $data, null, $extra);
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
            in_array($key, $transformer->getIncludes(), true) &&
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
     * @param $val
     * @param $data
     * @param string|null $key
     * @param array $extra
     * @return mixed
     */
    public function parseValue($val, $data, string $key = null, array $extra = [])
    {
        if (TransformerHelper::isTransformer($val)) {
            $args = [$data, $this, $key];

            if (!empty($extra)) {
                $args = array_merge(
                    $args,
                    $this->validParams($val, $extra)
                );
            }

            return call_user_func_array($val, $args);
        }

        return $val;
    }

    /**
     * @param $transformer
     * @param array $params
     * @return array
     */
    private function validParams($transformer, array $params): array
    {
        if (!is_object($transformer)) {
            return $params;
        }

        $method = new \ReflectionMethod($transformer, '__invoke');

        $ignore = ['data', 'scope', 'identifier'];
        $args = [];
        $missing = [];
        $actionParams = [];
        foreach ($method->getParameters() as $param) {
            $name = $param->name;
            if (true === in_array($name, $ignore, true)) {
                continue;
            }
            if (array_key_exists($name, $params)) {
                if ($param->isArray()) {
                    $args[] = $actionParams[$name] = (array) $params[$name];
                } elseif (!is_array($params[$name])) {
                    $args[] = $actionParams[$name] = $params[$name];
                } else {
                    throw new InvalidArgumentException(sprintf(
                        'Invalid data received for parameter "%s".',
                        $name
                    ));
                }
                unset($params[$name]);
            } elseif ($param->isDefaultValueAvailable()) {
                $args[] = $actionParams[$name] = $param->getDefaultValue();
            } else {
                $missing[] = $name;
            }
        }

        if (!empty($missing)) {
            throw new InvalidArgumentException(sprintf(
                'Missing required parameters "%s".',
                implode(', ', $missing)
            ));
        }

        return $args;
    }

    /**
     * @param string $identifier
     * @return Scope
     */
    public function childScope(string $identifier): Scope
    {
        $parentScopes = $this->getParentScopes();
        $parentScopes[] = $this->getScopeIdentifier();

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
    public function filterFields(array $data): array
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
            $this->getScopeIdentifier()
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
