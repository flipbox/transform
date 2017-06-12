<?php

/**
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace Flipbox\Transform;

use Flipbox\Transform\Resources\Collection;
use Flipbox\Transform\Resources\Item;
use Flipbox\Transform\Helpers\Object as ObjectHelper;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Transform
{
    /**
     * The character used to separate modifier parameters.
     *
     * @var string
     */
    public $paramDelimiter = '|';

    /**
     * Upper limit to how many levels of included data are allowed.
     *
     * @var int
     */
    public $recursionLimit = 10;

    /**
     * Scope identifiers that resources can optionally include.
     *
     * @var array
     */
    protected $includes = [];

    /**
     * Scope identifiers that resources must exclude.
     *
     * @var array
     */
    protected $excludes = [];

    /**
     * Scope identifiers that resources must return.
     *
     * @var array
     */
    protected $fields = [];

    /**
     * Array containing modifiers as keys and an array value of params.
     *
     * @var array
     */
    protected $params = [];

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        ObjectHelper::configure($this, $config);
    }

    /**
     * @param string $include
     *
     * @return ParamBag
     */
    public function getParams($include): ParamBag
    {
        $params = isset($this->params[$include]) ? $this->params[$include] : [];
        return new ParamBag($params);
    }

    /*******************************************
     * INCLUDES
     *******************************************/

    /**
     * Get Requested Includes.
     *
     * @return array
     */
    public function getIncludes(): array
    {
        return $this->includes;
    }

    /**
     * Parse Include String.
     *
     * @param array|string $includes Array or csv string of resources to include
     *
     * @return $this
     */
    public function setIncludes($includes)
    {
        // Wipe these before we go again
        $this->includes = $this->params = [];

        if (is_string($includes)) {
            $includes = explode(',', $includes);
        }

        if (!is_array($includes)) {
            throw new \InvalidArgumentException(
                'The parseIncludes() method expects a string or an array. ' . gettype($includes) . ' given'
            );
        }

        foreach ($includes as $include) {
            list($includeName, $allModifiersStr) = array_pad(explode(':', $include, 2), 2, null);

            // Trim it down to a cool level of recursion
            $includeName = $this->trimToAcceptableRecursionLevel($includeName);

            if (in_array($includeName, $this->includes)) {
                continue;
            }
            $this->includes[] = $includeName;

            // No Params? Bored
            if ($allModifiersStr === null) {
                continue;
            }

            // Matches multiple instances of 'something(foo|bar|baz)' in the string
            // I guess it ignores : so you could use anything, but probably don't do that
            preg_match_all('/([\w]+)(\(([^\)]+)\))?/', $allModifiersStr, $allModifiersArr);

            // [0] is full matched strings...
            $modifierCount = count($allModifiersArr[0]);

            $modifierArr = [];

            for ($modifierIt = 0; $modifierIt < $modifierCount; $modifierIt++) {
                // [1] is the modifier
                $modifierName = $allModifiersArr[1][$modifierIt];

                // and [3] is delimited params
                $modifierParamStr = $allModifiersArr[3][$modifierIt];

                // Make modifier array key with an array of params as the value
                $modifierArr[$modifierName] = explode($this->paramDelimiter, $modifierParamStr);
            }

            $this->params[$includeName] = $modifierArr;
        }

        // This should be optional and public someday, but without it includes would never show up
        $this->autoIncludeParents();

        return $this;
    }

    /*******************************************
     * EXCLUDES
     *******************************************/

    /**
     * Get Requested Excludes.
     *
     * @return array
     */
    public function getExcludes(): array
    {
        return $this->excludes;
    }

    /**
     * Parse Exclude String.
     *
     * @param array|string $excludes Array or csv string of resources to exclude
     *
     * @return $this
     */
    public function setExcludes($excludes)
    {
        $this->excludes = [];

        if (is_string($excludes)) {
            $excludes = explode(',', $excludes);
        }

        if (!is_array($excludes)) {
            throw new \InvalidArgumentException(
                'The parseExcludes() method expects a string or an array. ' . gettype($excludes) . ' given'
            );
        }

        foreach ($excludes as $excludeName) {
            $excludeName = $this->trimToAcceptableRecursionLevel($excludeName);

            if (in_array($excludeName, $this->excludes)) {
                continue;
            }

            $this->excludes[] = $excludeName;
        }

        return $this;
    }

    /*******************************************
     * FIELDS
     *******************************************/

    /**
     * Parse field parameter.
     *
     * @param array $fields Array of fields to include. It must be an array
     *                         whose keys are resource types and values a string
     *                         of the fields to return, separated by a comma
     *
     * @return $this
     */
    public function setFields(array $fields)
    {
        $this->fields = [];

        foreach ($fields as $type => $field) {
            //Remove empty and repeated fields
            $this->fields[$type] = array_unique(array_filter(explode(',', $field)));
        }

        return $this;
    }

    /**
     * Get requested fields.
     *
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * Get field params for the specified type.
     *
     * @param string $type
     *
     * @return ParamBag|null
     */
    public function getField($type)
    {
        return !isset($this->fields[$type]) ?
            null :
            new ParamBag($this->fields[$type]);
    }


    /*******************************************
     * RESOURCES
     *******************************************/

    /**
     * @param callable $transformer
     * @param $data
     * @return mixed
     */
    public function item(callable $transformer, $data)
    {
        return (new Item(
            new Scope($this)
        ))->transform($transformer, $data);
    }

    /**
     * @param callable $transformer
     * @param $data
     * @return mixed
     */
    public function collection(callable $transformer, $data)
    {
        return (new Collection(
            new Scope($this)
        ))->transform($transformer, $data);
    }


    /**
     * Auto-include Parents
     *
     * Look at the requested includes and automatically include the parents if they
     * are not explicitly requested. E.g: [foo, bar.baz] becomes [foo, bar, bar.baz]
     *
     * @internal
     *
     * @return void
     */
    protected function autoIncludeParents()
    {
        $parsed = [];

        foreach ($this->includes as $include) {
            $nested = explode('.', $include);

            $part = array_shift($nested);
            $parsed[] = $part;

            while (count($nested) > 0) {
                $part .= '.' . array_shift($nested);
                $parsed[] = $part;
            }
        }

        $this->includes = array_values(array_unique($parsed));
    }

    /**
     * Trim to Acceptable Recursion Level
     *
     * @internal
     *
     * @param string $includeName
     *
     * @return string
     */
    protected function trimToAcceptableRecursionLevel($includeName)
    {
        return implode('.', array_slice(explode('.', $includeName), 0, $this->recursionLimit));
    }
}
