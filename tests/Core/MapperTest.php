<?php

namespace Zeeml\DataSet\Tests\Core;

use PHPUnit\Framework\TestCase;
use Zeeml\DataSet\Core\Policy;
use Zeeml\DataSet\Core\Mapper;

class MapperTest extends TestCase
{
    /**
     * @test
     */
    public function map_with_policy_none_should_return_instance()
    {
        $mapper = new Mapper([0 => Policy::none(), 1 => Policy::none()], [2 => Policy::none()]);
        $row = [1, null, 3, 4];

        list($dimensions, $outputs) = $mapper->map($row);
        $this->assertInternalType('array', $dimensions);
        $this->assertInternalType('array', $outputs);
        $this->assertCount(2, $dimensions);
        $this->assertEquals([0 => 1, 1 => null], $dimensions);
        $this->assertEquals([2 => 3], $outputs);

    }

    /**
     * @test
     */
    public function map_with_skip_policy_should_return_null_for_empty_values()
    {
        $mapper = new Mapper([0 => Policy::skip(), 1 => Policy::skip()], [2 => Policy::skip()]);
        $row = [1, null, 3, 4];

        $instance = $mapper->map($row);
        $this->assertNull($instance);
    }

    /**
     * @test
     */
    public function map_with_skip_policy_should_return_instance_if_no_empty_values()
    {
        $mapper = new Mapper([0 => Policy::skip(), 1 => Policy::skip()], [2 => Policy::skip()]);
        $row = [1, 2, 3, 4];
        list($dimensions, $outputs) = $mapper->map($row);
        $this->assertInternalType('array', $dimensions);
        $this->assertInternalType('array', $outputs);
        $this->assertEquals([0 => 1, 1 => 2], $dimensions);
        $this->assertEquals([2 => 3], $outputs);
    }

    /**
     * @test
     */
    public function map_with_replace_policy_should_replace_empty_values()
    {
        $mapper = new Mapper(
            [
                0 => Policy::replaceWith('A'),
                1 => Policy::replaceWith('B'),
                3 => Policy::replaceWith('C'),
            ],
            [
                1 => Policy::replaceWith('D'),
                2 => Policy::replaceWith('E'),

            ]
        );

        $row = [1, null, 3, null];
        list($dimensions, $outputs) = $mapper->map($row);
        $this->assertInternalType('array', $dimensions);
        $this->assertInternalType('array', $outputs);
        $this->assertEquals([0 => 1, 1 => 'B', 3 => 'C'], $dimensions);
        $this->assertEquals([1 => 'D', 2 => 3], $outputs);
    }

    /**
     * @test
     */
    public function map_with_replaceWithAvg_policy_should_replace_empty_values_with_avg()
    {
        $mapper = new Mapper(
            [
                0 => Policy::replaceWithAvg(),
                1 => Policy::replaceWithAvg(),
                3 => Policy::replaceWithAvg(),
            ],
            [
                1 => Policy::replaceWithAvg(),
                2 => Policy::replaceWithAvg(),

            ]
        );

        $row = [1, null, 3, null];
        list($dimensions, $outputs) = $mapper->map($row);
        $this->assertInternalType('array', $dimensions);
        $this->assertInternalType('array', $outputs);
        $this->assertEquals([0 => 1, 1 => Policy::AVG, 3 => Policy::AVG], $dimensions);
        $this->assertEquals([1 => Policy::AVG, 2 => 3], $outputs);
    }

    /**
     * @test
     */
    public function map_with_different_policies_should_all_work()
    {
        $mapper = new Mapper(
            [
                0 => [Policy::replaceWithAvg(), Policy::rename('Dimension 0')],
                1 => [Policy::replaceWithAvg(), Policy::rename('Dimension 1')],
                3 => [Policy::replaceWithMostCommon(), Policy::rename('Dimension 2')],
            ],
            [
                1 => [Policy::replaceWithAvg(), Policy::rename('Output 0')],
                2 => [Policy::replaceWithAvg(), Policy::rename('Output 1')],
            ]
        );

        $row = [1, null, 3, null];
        list($dimensions, $outputs) = $mapper->map($row);
        $this->assertInternalType('array', $dimensions);
        $this->assertInternalType('array', $outputs);
        $this->assertEquals(
            [
                'Dimension 0' => 1,
                'Dimension 1' => Policy::AVG,
                'Dimension 2' => Policy::MOST_COMMON,
            ],
            $dimensions
        );
        $this->assertEquals(
            [
                'Output 0' => Policy::AVG,
                'Output 1' => 3,
            ],
            $outputs
        );
    }
    /**
     * @test
     */
    public function map_with_different_policies_and_skip_should_skip()
    {
        $mapper = new Mapper(
            [
                0 => [Policy::replaceWithAvg(), Policy::rename('Dimension 0')],
                1 => [Policy::skip(), Policy::rename('Dimension 1')],
                3 => [Policy::replaceWithMostCommon(), Policy::rename('Dimension 2')],
            ],
            [
                1 => [Policy::replaceWithAvg(), Policy::rename('Output 0')],
                2 => [Policy::replaceWithAvg(), Policy::rename('Output 1')],
            ]
        );

        $row = [1, null, 3, null];
        list($dimensions, $outputs) = $mapper->map($row);

        $this->assertNull($dimensions);
        $this->assertNull($outputs);
    }

}
