<?php

use Zeeml\DataSet\DataSetFactory;
use PHPUnit\Framework\TestCase;
use Zeeml\DataSet\Core\Mapper;

/**
 * Core test case.
 */
class DataSetTest extends TestCase
{
    /**
     *
     * @var \Zeeml\DataSet\DataSet
     */
    private $dataSet;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->dataSet = DataSetFactory::create( __DIR__ . '/fixtures/data.csv');
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->dataSet = null;
        parent::tearDown();
    }

    /**
     * @test
     */
    public function dataset_is_iterable()
    {
        $this->assertInstanceOf(\Iterator::class, $this->dataSet);
    }

    /**
     * @test
     */
    public function default_behavior_is_preserve_keys()
    {
        $mapper = new Mapper([2,1], [2]);
        $this->dataSet->prepare($mapper);

        $this->assertEquals(
            $this->dataSet->getRawDimensions(),
            [
                [2 => 'I',    1 => 'A'],
                [2 => 'II',   1 => 'B'],
                [2 => 'III',  1 => 'C '],
                [2 => 'IV',   1 => 'D'],
                [2 => 'V',    1 => 'E'],
                [2 => 'VI',   1 => 'F'],
                [2 => 'VII',  1 => 'G'],
                [2 => 'VIII', 1 => 'H'],
                [2 => 'IX',   1 => 'I'],
                [2 => 'X',    1 => 'J'],
            ]
        );

        $this->assertEquals(
            $this->dataSet->getRawOutputs(),
            [
                [2 => 'I'],
                [2 => 'II'],
                [2 => 'III'],
                [2 => 'IV'],
                [2 => 'V'],
                [2 => 'VI'],
                [2 => 'VII'],
                [2 => 'VIII'],
                [2 => 'IX'],
                [2 => 'X'],
            ]
        );
    }

    /**
     * @test
     */
    public function resets_keys_when_asked_to()
    {
        $mapper = new Mapper([2,1], [2]);
        $this->dataSet->prepare($mapper, false);

        $this->assertEquals(
            $this->dataSet->getRawDimensions(),
            [
                ['I',    'A'],
                ['II',   'B'],
                ['III',  'C '],
                ['IV',   'D'],
                ['V',    'E'],
                ['VI',   'F'],
                ['VII',  'G'],
                ['VIII', 'H'],
                ['IX',   'I'],
                ['X',    'J'],
            ]
        );

        $this->assertEquals(
            $this->dataSet->getRawOutputs(),
            [
                ['I'],
                ['II'],
                ['III'],
                ['IV'],
                ['V'],
                ['VI'],
                ['VII'],
                ['VIII'],
                ['IX'],
                ['X'],
            ]
        );
    }

    /**
     * @test
     */
    public function method_prepare_sets_a_proper_array_of_instances()
    {
        $this->assertFalse($this->dataSet->isPrepared());

        $mapper = new Mapper([0,1], [2]);
        $this->dataSet->prepare($mapper);

        $this->assertTrue($this->dataSet->isPrepared());

        $this->assertInstanceOf(Mapper::class, $this->dataSet->getMapper());

        $this->assertInternalType('array', $this->dataSet->getInstances());
        $this->assertEquals(10, count($this->dataSet->getInstances()));

        foreach ($this->dataSet->getInstances() as $instance) {
            // any instance should contain two dimensions
            $this->assertEquals(2, count($instance->getDimensions()));
            // any instance should contain one output
            $this->assertEquals(1, count($instance->getOutputs()));
        }

        $this->assertEquals(
            $this->dataSet->getRawDimensions(),
            [
                [1, 'A'],
                [2, 'B'],
                [3, 'C '],
                [4, 'D'],
                [5, 'E'],
                [6, 'F'],
                [7, 'G'],
                [8, 'H'],
                [9, 'I'],
                [10, 'J'],
            ]
        );

        $this->assertEquals(
            $this->dataSet->getRawOutputs(),
            [
                [2 => 'I'],
                [2 => 'II'],
                [2 => 'III'],
                [2 => 'IV'],
                [2 => 'V'],
                [2 => 'VI'],
                [2 => 'VII'],
                [2 => 'VIII'],
                [2 => 'IX'],
                [2 => 'X'],
            ]
        );

    }

    /**
     * @test
     * @expectedException Zeeml\DataSet\Exception\DataSetPreparationException
     */
    public function method_prepare_fails_whith_bad_params()
    {
        $mapper = new Mapper([3], [1]); // no key 3 in fixture
        $this->dataSet->prepare($mapper);
    }

    /**
     * @test
     */
    public function method_getData_returns_a_data_array()
    {
        $this->assertInternalType('array', $this->dataSet->getData());
        $this->assertCount(10, $this->dataSet->getData());
        $this->assertEquals(
            [
                [1, 'A', 'I'],
                [2, 'B', 'II'],
                [3, 'C ' , 'III'],
                [4, 'D', 'IV'],
                [5, 'E', 'V'],
                [6, 'F', 'VI'],
                [7, 'G', 'VII'],
                [8, 'H', 'VIII'],
                [9, 'I', 'IX'],
                [10, 'J','X'],
            ],
            $this->dataSet->getData()
        );
    }

    /**
     * @test
     */
    public function method_size_returns_an_integer()
    {
        $this->assertEquals(10, $this->dataSet->getSize());
    }

    /**
     * @test
     * @expectedException Zeeml\DataSet\Exception\DataSetPreparationException
     */
    public function direct_call_to_getMapper_fails()
    {
        $this->dataSet->getMapper();
    }

    /**
     * @test
     */
    public function call_getMapper_after_preparation_succeeds()
    {
        $this->dataSet->prepare(new Mapper([0, 1], [2]));
        $this->assertInstanceOf(Mapper::class, $this->dataSet->getMapper());
    }

    /**
     * @test
     * @expectedException Zeeml\DataSet\Exception\DataSetPreparationException
     */
    public function direct_call_to_getInstances_fails()
    {
        $this->dataSet->getInstances();
    }

    /**
     * @test
     */
    public function call_getInstances_after_preparation_succeeds()
    {
        $this->dataSet->prepare(new Mapper([0, 1], [2]));
        $this->assertInternalType('array', $this->dataSet->getInstances());
        $this->assertCount(10, $this->dataSet->getInstances());
    }

    /**
     * @test
     * @expectedException Zeeml\DataSet\Exception\DataSetPreparationException
     */
    public function direct_call_to_getRawDimensions_fails()
    {
        $this->dataSet->getRawDimensions();
    }


    /**
     * @test
     */
    public function call_getRawDimensions_after_preparation_succeeds()
    {
        $this->dataSet->prepare(new Mapper([0, 1], [2]));
        $this->assertInternalType('array', $this->dataSet->getRawDimensions());
        $this->assertCount(10, $this->dataSet->getRawDimensions());
    }

    /**
     * @test
     * @expectedException Zeeml\DataSet\Exception\DataSetPreparationException
     */
    public function direct_call_to_getRawOutputs_fails()
    {
        $this->dataSet->getRawDimensions();
    }


    /**
     * @test
     */
    public function call_getRawOutputs_after_preparation_succeeds()
    {
        $this->dataSet->prepare(new Mapper([0, 1], [2]));
        $this->assertInternalType('array', $this->dataSet->getRawOutputs());
        $this->assertCount(10, $this->dataSet->getRawOutputs());
    }
}
