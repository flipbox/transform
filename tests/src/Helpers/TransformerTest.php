<?php

/**
 * @author    Flipbox Factory
 * @copyright Copyright (c) 2017, Flipbox Digital
 * @link      https://github.com/flipbox/transform/releases/latest
 * @license   https://github.com/flipbox/transform/blob/master/LICENSE
 */

namespace Flipbox\Transform\Tests\Helpers;

use Flipbox\Transform\Helpers\TransformerHelper;
use Flipbox\Transform\Tests\DummyClass;
use Flipbox\Transform\Tests\InheritTransformer;
use Flipbox\Transform\Tests\TestTransformer;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
class TransformerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * We're looking for anything that can be transformed
     *
     * @test
     */
    public function isTransformerTest()
    {
        $data = $this->transformerData();

        $this->assertEquals(
            false,
            TransformerHelper::isTransformable($data[0])
        );

        $this->assertEquals(
            true,
            TransformerHelper::isTransformable($data[1])
        );

        $this->assertEquals(
            true,
            TransformerHelper::isTransformable($data[2])
        );

        $this->assertEquals(
            false,
            TransformerHelper::isTransformable($data[3])
        );

        $this->assertEquals(
            true,
            TransformerHelper::isTransformable($data[4])
        );

        $this->assertEquals(
            false,
            TransformerHelper::isTransformable($data[5])
        );

        $this->assertEquals(
            false,
            TransformerHelper::isTransformable($data[6])
        );

        $this->assertEquals(
            false,
            TransformerHelper::isTransformable($data[7])
        );

        $this->assertEquals(
            true,
            TransformerHelper::isTransformable($data[8])
        );
    }

    /**
     * We're looking for an anonymous function only
     *
     * @test
     */
    public function isClosureTest()
    {
        $data = $this->transformerData();

        $this->assertEquals(
            false,
            TransformerHelper::isClosure($data[0])
        );

        $this->assertEquals(
            true,
            TransformerHelper::isClosure($data[1])
        );

        $this->assertEquals(
            false,
            TransformerHelper::isClosure($data[2])
        );

        $this->assertEquals(
            false,
            TransformerHelper::isClosure($data[3])
        );

        $this->assertEquals(
            false,
            TransformerHelper::isClosure($data[4])
        );

        $this->assertEquals(
            false,
            TransformerHelper::isClosure($data[5])
        );

        $this->assertEquals(
            false,
            TransformerHelper::isClosure($data[6])
        );

        $this->assertEquals(
            false,
            TransformerHelper::isClosure($data[7])
        );

        $this->assertEquals(
            false,
            TransformerHelper::isClosure($data[8])
        );
    }

    /**
     * We're looking for an object that is callable (via __invoke)
     *
     * @test
     */
    public function isInvokableTest()
    {
        $data = $this->transformerData();

        $this->assertEquals(
            false,
            TransformerHelper::isInvokable($data[0])
        );

        $this->assertEquals(
            false,
            TransformerHelper::isInvokable($data[1])
        );

        $this->assertEquals(
            true,
            TransformerHelper::isInvokable($data[2])
        );

        $this->assertEquals(
            false,
            TransformerHelper::isInvokable($data[3])
        );

        $this->assertEquals(
            true,
            TransformerHelper::isInvokable($data[4])
        );

        $this->assertEquals(
            false,
            TransformerHelper::isInvokable($data[5])
        );

        $this->assertEquals(
            false,
            TransformerHelper::isInvokable($data[6])
        );

        $this->assertEquals(
            false,
            TransformerHelper::isInvokable($data[7])
        );

        $this->assertEquals(
            false,
            TransformerHelper::isInvokable($data[8])
        );
    }

    /**
     * We're looking for an anonymous function only
     *
     * @test
     */
    public function isCallableArrayTest()
    {
        $data = $this->transformerData();

        $this->assertEquals(
            false,
            TransformerHelper::isCallableArray($data[0])
        );

        $this->assertEquals(
            false,
            TransformerHelper::isCallableArray($data[1])
        );

        $this->assertEquals(
            false,
            TransformerHelper::isCallableArray($data[2])
        );

        $this->assertEquals(
            false,
            TransformerHelper::isCallableArray($data[3])
        );

        $this->assertEquals(
            false,
            TransformerHelper::isCallableArray($data[4])
        );

        $this->assertEquals(
            false,
            TransformerHelper::isCallableArray($data[5])
        );

        $this->assertEquals(
            false,
            TransformerHelper::isCallableArray($data[6])
        );

        $this->assertEquals(
            false,
            TransformerHelper::isCallableArray($data[7])
        );

        $this->assertEquals(
            true,
            TransformerHelper::isCallableArray($data[8])
        );
    }

    /**
     * We're looking for a full class name which, when created, can be a callable
     *
     * @test
     */
    public function isTransformerClassTest()
    {
        $data = $this->transformerData();

        $this->assertEquals(
            false,
            TransformerHelper::isTransformerClass($data[0])
        );

        $this->assertEquals(
            false,
            TransformerHelper::isTransformerClass($data[1])
        );

        $this->assertEquals(
            false,
            TransformerHelper::isTransformerClass($data[2])
        );

        $this->assertEquals(
            true,
            TransformerHelper::isTransformerClass($data[3])
        );

        $this->assertEquals(
            false,
            TransformerHelper::isTransformerClass($data[4])
        );

        $this->assertEquals(
            true,
            TransformerHelper::isTransformerClass($data[5])
        );

        $this->assertEquals(
            false,
            TransformerHelper::isTransformerClass($data[6])
        );

        $this->assertEquals(
            false,
            TransformerHelper::isTransformerClass($data[7])
        );

        $this->assertEquals(
            false,
            TransformerHelper::isTransformerClass($data[8])
        );
    }

    /**
     * We're looking for a full class name which, when created, can be a callable
     *
     * @test
     */
    public function resolveTest()
    {
        $data = $this->transformerData();

        $this->assertNull(
            TransformerHelper::resolve($data[0])
        );

        $this->assertInstanceOf(
            \Closure::class,
            TransformerHelper::resolve($data[1])
        );

        $this->assertInstanceOf(
            TestTransformer::class,
            TransformerHelper::resolve($data[2])
        );

        $this->assertInstanceOf(
            TestTransformer::class,
            TransformerHelper::resolve($data[3])
        );

        $this->assertInstanceOf(
            InheritTransformer::class,
            TransformerHelper::resolve($data[4])
        );

        $this->assertInstanceOf(
            InheritTransformer::class,
            TransformerHelper::resolve($data[5])
        );

        $this->assertNull(
            TransformerHelper::resolve($data[6])
        );

        $this->assertNull(
            TransformerHelper::resolve($data[7])
        );

        $this->assertEquals(
            $data[8],
            TransformerHelper::resolve($data[8])
        );
    }


    protected function transformerData()
    {
        return [
            'foo', // string
            function() {}, // anonymous function
            new TestTransformer(), // __invoke(able)
            TestTransformer::class, // __invoke(able) class
            new InheritTransformer(), // __invoke(able)
            InheritTransformer::class, // __invoke(able) class
            new DummyClass(), // __invoke(able)
            DummyClass::class, // __invoke(able) class
            [
                DummyClass::class,
                'test'
            ]
        ];
    }
}