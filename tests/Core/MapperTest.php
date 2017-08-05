<?php

namespace Zeeml\DataSet\Tests\Core;

use PHPUnit\Framework\TestCase;
use Zeeml\DataSet\Core\CleanPolicy;
use Zeeml\DataSet\Core\Instance;
use Zeeml\DataSet\Core\Mapper;

class MapperTest extends TestCase
{
    /**
     * @test
     */
    public function map_with_policy_none_should_return_instance()
    {
        $mapper = new Mapper([0 => CleanPolicy::none(), 1 => CleanPolicy::none()], [2 => CleanPolicy::none()]);

        $instance = $mapper->map([1, null, 3, 4]);
        $this->assertInternalType('array', $instance);
        $this->assertCount(2, $instance[0]);
        $this->assertEquals([0 => 1, 1 => null], $instance[0]);
        $this->assertEquals([2 => 3], $instance[1]);

    }

    /**
     * @test
     */
    public function map_with_skip_policy_should_return_null_for_empty_values()
    {
        $mapper = new Mapper([0 => CleanPolicy::skip(), 1 => CleanPolicy::skip()], [2 => CleanPolicy::skip()]);

        $instance = $mapper->map([1, null, 3, 4]);
        $this->assertNull($instance);
    }

    /**
     * @test
     */
    public function map_with_skip_policy_should_return_instance_if_no_empty_values()
    {
        $mapper = new Mapper([0 => CleanPolicy::skip(), 1 => CleanPolicy::skip()], [2 => CleanPolicy::skip()]);
        $instance = $mapper->map([1, 2, 3, 4]);
        $this->assertInternalType('array', $instance);
        $this->assertEquals([0 => 1, 1 => 2], $instance[0]);
        $this->assertEquals([2 => 3], $instance[1]);
    }

    /**
     * @test
     */
    public function map_with_replace_policy_should_replace_empty_values()
    {
        $mapper = new Mapper(
            [
                0 => CleanPolicy::replaceWith('A'),
                1 => CleanPolicy::replaceWith('B'),
                3 => CleanPolicy::replaceWith('C'),
            ],
            [
                1 => CleanPolicy::replaceWith('D'),
                2 => CleanPolicy::replaceWith('E'),

            ]
        );

        $instance = $mapper->map([1, null, 3, null]);
        $this->assertInternalType('array', $instance);
        $this->assertEquals([0 => 1, 1 => 'B', 3 => 'C'], $instance[0]);
        $this->assertEquals([1 => 'D', 2 => 3], $instance[1]);
    }

    /**
     * @test
     */
    public function map_with_replaceWithAvg_policy_should_replace_empty_values_with_avg()
    {
        $mapper = new Mapper(
            [
                0 => CleanPolicy::replaceWithAvg(),
                1 => CleanPolicy::replaceWithAvg(),
                3 => CleanPolicy::replaceWithAvg(),
            ],
            [
                1 => CleanPolicy::replaceWithAvg(),
                2 => CleanPolicy::replaceWithAvg(),

            ]
        );

        $instance = $mapper->map([1, null, 3, null]);
        $this->assertInternalType('array', $instance);
        $this->assertEquals([0 => 1, 1 => CleanPolicy::AVG, 3 => CleanPolicy::AVG], $instance[0]);
        $this->assertEquals([1 => CleanPolicy::AVG, 2 => 3], $instance[1]);
    }
}
