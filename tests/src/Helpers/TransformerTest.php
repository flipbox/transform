<?php

/**
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace Flipbox\Transform\Tests\Helpers;

use Flipbox\Transform\Helpers\Transformer;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
class TransformerTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function createResolverTest()
    {
        $this->assertEquals(false, Transformer::isTransformer('foo'));
        $this->assertEquals(true, Transformer::isTransformer(function() {}));
    }
}