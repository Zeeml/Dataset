<?php

namespace Zeeml\DataSet\Tests\DataSet;

use PHPUnit\Framework\TestCase;
use Zeeml\DataSet\DataSet\Instance;
use Zeeml\DataSet\DataSet\Mapper;

class MapperTest extends TestCase
{
    /**
     * @test
     * @expectedException \Zeeml\DataSet\Exception\DataSetPreparationException
     */
    public function createInstance_fails_if_mapper_created_with_nonExisting_keys()
    {
        $mapper = new Mapper([4], [5]);
        $mapper->createInstance([1, 2, 3]);
    }

    /**
     * @test
     * @expectedException \Zeeml\DataSet\Exception\DataSetPreparationException
     */
    public function createInstance_fails_if_mapper_created_with_wrong_output_key()
    {
        $mapper = new Mapper([0], [5]);
        $mapper->createInstance([1, 2, 3]);
    }

    /**
     * @test
     * @expectedException \Zeeml\DataSet\Exception\DataSetPreparationException
     */
    public function createInstance_fails_if_mapper_created_with_wrong_dimension_key()
    {
        $mapper = new Mapper([4], [1]);
        $mapper->createInstance([1, 2, 3]);
    }

    /**
     * @test
     */
    public function createInstance_with_right_keys_work()
    {
        $mapper = new Mapper([0, 1], [2]);
        $instance = $mapper->createInstance([1, 2, 3]);

        $this->assertInstanceOf(Instance::class, $instance);

        $this->assertCount(2, $instance->getDimensions());
        $this->assertCount(1, $instance->getOutputs());
    }
}
