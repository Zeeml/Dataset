<?php

namespace Zeeml\DataSet\Tests\Core;

use PHPUnit\Framework\TestCase;
use Zeeml\DataSet\Core\Instance;
use Zeeml\DataSet\Core\Mapper;

class MapperTest extends TestCase
{
    /**
     * @test
     * @expectedException \Zeeml\DataSet\Exception\DataSetPreparationException
     */
    public function createInstance_fails_if_mapper_created_with_nonExisting_keys()
    {
        $mapper = new Mapper([4], [5]);
        $mapper->createInstance([1, 2, 3], true);
    }

    /**
     * @test
     * @expectedException \Zeeml\DataSet\Exception\DataSetPreparationException
     */
    public function createInstance_fails_if_mapper_created_with_wrong_output_key()
    {
        $mapper = new Mapper([0], [5]);
        $mapper->createInstance([1, 2, 3], true);
    }

    /**
     * @test
     * @expectedException \Zeeml\DataSet\Exception\DataSetPreparationException
     */
    public function createInstance_fails_if_mapper_created_with_wrong_dimension_key()
    {
        $mapper = new Mapper([4], [1]);
        $mapper->createInstance([1, 2, 3], true);
    }

    /**
     * @test
     */
    public function createInstance_with_right_keys_work()
    {
        $mapper = new Mapper([0, 1], [2]);
        $instance = $mapper->createInstance([1, 2, 3], true);

        $this->assertInstanceOf(Instance::class, $instance);

        $this->assertCount(2, $instance->getDimensions());
        $this->assertCount(1, $instance->getOutputs());
    }

    /**
     * @test
     */
    public function should_preserve_the_keys_when_asked_to()
    {
        $mapper = new Mapper([2, 1], [2]);
        $instance = $mapper->createInstance([1, 2, 3], true);

        $this->assertInstanceOf(Instance::class, $instance);

        $this->assertCount(2, $instance->getDimensions());
        $this->assertCount(1, $instance->getOutputs());

        $this->assertEquals([2 => 3, 1 => 2], $instance->getDimensions());
        $this->assertEquals([2 => 3], $instance->getOutputs());
    }

    /**
     * @test
     */
    public function should_reset_the_keys_when_asked_to()
    {
        $mapper = new Mapper([2, 1], [2]);
        $instance = $mapper->createInstance([1, 2, 3], false);

        $this->assertInstanceOf(Instance::class, $instance);

        $this->assertCount(2, $instance->getDimensions());
        $this->assertCount(1, $instance->getOutputs());

        $this->assertEquals([3, 2], $instance->getDimensions());
        $this->assertEquals([3], $instance->getOutputs());
    }
}
