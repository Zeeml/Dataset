<?php

use Zeeml\DataSet\DataSetFactory;
use PHPUnit\Framework\TestCase;
use Zeeml\DataSet\Core\Mapper;
use Zeeml\DataSet\Core\CleanPolicy;
use Zeeml\DataSet\Core\Instance;

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

    private $csvSize;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->dataSet = DataSetFactory::create( __DIR__ . '/fixtures/data.csv');
        $this->csvSize = 11;
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
    public function default_behavior_is_without_cleaner()
    {
        //Preserving keys
        $mapper = new Mapper(['col3','col2'], ['col3']);
        $this->dataSet->prepare($mapper);

        $expectedInputs = [
            ['col3' => 'I',    'col2' => 'A'],
            ['col3' => 'II',   'col2' => 'B'],
            ['col3' => 'III',  'col2' => 'C '],
            ['col3' => 'IV',   'col2' => 'D'],
            ['col3' => 'V',    'col2' => ''],
            ['col3' => '',     'col2' => 'F'],
            ['col3' => 'VII',  'col2' => 'G'],
            ['col3' => 'VIII', 'col2' => 'H'],
            ['col3' => 'IX',   'col2' => 'I'],
            ['col3' => 'X',    'col2' => 'J'],
            ['col3' => 'I',    'col2' => 'A'],
        ];

        $expectedOutputs = [
            ['col3' => 'I'],
            ['col3' => 'II'],
            ['col3' => 'III'],
            ['col3' => 'IV'],
            ['col3' => 'V'],
            ['col3' => ''],
            ['col3' => 'VII'],
            ['col3' => 'VIII'],
            ['col3' => 'IX'],
            ['col3' => 'X'],
            ['col3' => 'I'],
        ];

        $this->assertEquals($expectedInputs, $this->dataSet->getInputsMatrix());
        $this->assertEquals($expectedOutputs, $this->dataSet->getOutputsMatrix() );
        $this->assertEquals(count($expectedInputs), $this->dataSet->getSize());
        $this->assertEquals(count($this->dataSet->getInputsMatrix()), count($this->dataSet->getOutputsMatrix()));
        $this->assertEquals(0, $this->dataSet->getInputAvg(2));
        $this->assertEquals(0, $this->dataSet->getInputAvg(1));
        $this->assertEquals(['col3' => 0, 'col2' => 0], $this->dataSet->getInputsAvg());
        $this->assertEquals(['col3' => 0], $this->dataSet->getOutputsAvg());
        $this->assertEquals(0, $this->dataSet->getOutputAvg(2));

        foreach ($this->dataSet as $index => $instance) {
            $this->assertInstanceOf(Instance::class, $instance);
            $this->assertEquals($expectedInputs[$index], $instance->getInputs());
            $this->assertEquals($expectedOutputs[$index], $instance->getOutputs());

        }
    }

    /**
     * @test
     */
    public function applying_none_policy_does_nothing()
    {
        //preserving keys
        $mapper = new Mapper(['col3' => CleanPolicy::none(),'col2' => CleanPolicy::none()], ['col3' => CleanPolicy::none()]);
        $this->dataSet->prepare($mapper);

        $expectedInputs = [
            ['col3' => 'I',    'col2' => 'A'],
            ['col3' => 'II',   'col2' => 'B'],
            ['col3' => 'III',  'col2' => 'C '],
            ['col3' => 'IV',   'col2' => 'D'],
            ['col3' => 'V',    'col2' => ''],
            ['col3' => '',     'col2' => 'F'],
            ['col3' => 'VII',  'col2' => 'G'],
            ['col3' => 'VIII', 'col2' => 'H'],
            ['col3' => 'IX',   'col2' => 'I'],
            ['col3' => 'X',    'col2' => 'J'],
            ['col3' => 'I',    'col2' => 'A'],
        ];

        $expectedOutputs = [
            ['col3' => 'I'],
            ['col3' => 'II'],
            ['col3' => 'III'],
            ['col3' => 'IV'],
            ['col3' => 'V'],
            ['col3' => ''],
            ['col3' => 'VII'],
            ['col3' => 'VIII'],
            ['col3' => 'IX'],
            ['col3' => 'X'],
            ['col3' => 'I'],
        ];

        $this->assertEquals($expectedInputs, $this->dataSet->getInputsMatrix());
        $this->assertEquals($expectedOutputs, $this->dataSet->getOutputsMatrix());

        $this->assertEquals(count($expectedInputs), $this->dataSet->getSize());
        $this->assertEquals(count($this->dataSet->getInputsMatrix()), count($this->dataSet->getOutputsMatrix()));
        $this->assertEquals(0, $this->dataSet->getInputAvg(2));
        $this->assertEquals(0, $this->dataSet->getInputAvg(1));
        $this->assertEquals(0, $this->dataSet->getOutputAvg(2));
        $this->assertEquals(['col3' => 0, 'col2' => 0], $this->dataSet->getInputsAvg());
        $this->assertEquals(['col3' => 0], $this->dataSet->getOutputsAvg());

        foreach ($this->dataSet as $index => $instance) {
            $this->assertInstanceOf(Instance::class, $instance);
            $this->assertEquals($expectedInputs[$index], $instance->getInputs());
            $this->assertEquals($expectedOutputs[$index], $instance->getOutputs());

        }
    }

    /**
     * @test
     */
    public function applying_skip_policy_remove_empty_lines()
    {
        //preserving keys
        $mapper = new Mapper(['col3' => CleanPolicy::skip(), 'col2' => CleanPolicy::skip()], ['col3' => CleanPolicy::skip()]);
        $this->dataSet->prepare($mapper);

        $expectedInputs = [
            ['col3' => 'I', 'col2' => 'A'],
            ['col3' => 'II', 'col2' => 'B'],
            ['col3' => 'III', 'col2' => 'C '],
            ['col3' => 'IV', 'col2' => 'D'],
            ['col3' => 'VII', 'col2' => 'G'],
            ['col3' => 'VIII', 'col2' => 'H'],
            ['col3' => 'IX', 'col2' => 'I'],
            ['col3' => 'X', 'col2' => 'J'],
            ['col3' => 'I', 'col2' => 'A'],
        ];

        $expectedOutputs = [
            ['col3' => 'I'],
            ['col3' => 'II'],
            ['col3' => 'III'],
            ['col3' => 'IV'],
            ['col3' => 'VII'],
            ['col3' => 'VIII'],
            ['col3' => 'IX'],
            ['col3' => 'X'],
            ['col3' => 'I'],
        ];

        $this->assertEquals($expectedInputs, $this->dataSet->getInputsMatrix());
        $this->assertEquals($expectedOutputs, $this->dataSet->getOutputsMatrix());

        $this->assertEquals(count($expectedInputs), $this->dataSet->getSize());
        $this->assertEquals(count($this->dataSet->getInputsMatrix()), count($this->dataSet->getOutputsMatrix()));
        $this->assertEquals(0, $this->dataSet->getInputAvg(2));
        $this->assertEquals(0, $this->dataSet->getInputAvg(1));
        $this->assertEquals(0, $this->dataSet->getOutputAvg(2));
        $this->assertEquals(['col3' => 0, 'col2' => 0], $this->dataSet->getInputsAvg());
        $this->assertEquals(['col3' => 0], $this->dataSet->getOutputsAvg());

        foreach ($this->dataSet as $index => $instance) {
            $this->assertInstanceOf(Instance::class, $instance);
            $this->assertEquals($expectedInputs[$index], $instance->getInputs());
            $this->assertEquals($expectedOutputs[$index], $instance->getOutputs());

        }
    }

    /**
     * @test
     */
    public function applying_replaceWith_policy_replace_empty_values()
    {
        //preserving keys
        $mapper = new Mapper(
            [
                'col3' => CleanPolicy::replaceWith('TEST1'),
                'col2' => CleanPolicy::replaceWith('TEST2')
            ],
            [
                'col3' => CleanPolicy::replaceWith('TEST3')
            ]
        );
        $this->dataSet->prepare($mapper);

        $expectedInputs = [
            ['col3' => 'I',     'col2' => 'A'],
            ['col3' => 'II',    'col2' => 'B'],
            ['col3' => 'III',   'col2' => 'C '],
            ['col3' => 'IV',    'col2' => 'D'],
            ['col3' => 'V',     'col2' => 'TEST2'],
            ['col3' => 'TEST1', 'col2' => 'F'],
            ['col3' => 'VII',   'col2' => 'G'],
            ['col3' => 'VIII',  'col2' => 'H'],
            ['col3' => 'IX',    'col2' => 'I'],
            ['col3' => 'X',     'col2' => 'J'],
            ['col3' => 'I',     'col2' => 'A'],
        ];

        $expectedOutputs = [
            ['col3' => 'I'],
            ['col3' => 'II'],
            ['col3' => 'III'],
            ['col3' => 'IV'],
            ['col3' => 'V'],
            ['col3' => 'TEST3'],
            ['col3' => 'VII'],
            ['col3' => 'VIII'],
            ['col3' => 'IX'],
            ['col3' => 'X'],
            ['col3' => 'I'],
        ];

        $this->assertEquals($expectedInputs, $this->dataSet->getInputsMatrix());
        $this->assertEquals($expectedOutputs, $this->dataSet->getOutputsMatrix());

        $this->assertEquals(count($expectedInputs), $this->dataSet->getSize());
        $this->assertEquals(count($this->dataSet->getInputsMatrix()), count($this->dataSet->getOutputsMatrix()));
        $this->assertEquals(0, $this->dataSet->getInputAvg(2));
        $this->assertEquals(0, $this->dataSet->getInputAvg(1));
        $this->assertEquals(0, $this->dataSet->getOutputAvg(2));
        $this->assertEquals(['col3' => 0, 'col2' => 0], $this->dataSet->getInputsAvg());
        $this->assertEquals(['col3' => 0], $this->dataSet->getOutputsAvg());

        foreach ($this->dataSet as $index => $instance) {
            $this->assertInstanceOf(Instance::class, $instance);
            $this->assertEquals($expectedInputs[$index], $instance->getInputs());
            $this->assertEquals($expectedOutputs[$index], $instance->getOutputs());

        }
    }

    /**
     * @test
     */
    public function applying_replaceWithAvg_policy_replace_empty_values_with_avg()
    {
        //preserving keys
        $mapper = new Mapper(
            [
                'col1' => CleanPolicy::replaceWithAvg(),
                'col3' => CleanPolicy::replaceWithAvg(),
                'col2' => CleanPolicy::replaceWithAvg(),
            ],
            [
                'col3' => CleanPolicy::replaceWithAvg(),
            ]
        );

        $this->dataSet->prepare($mapper);

        $expectedInputs = [
            ['col1' => 1.0,   'col3' => 'I',     'col2' => 'A'],
            ['col1' => 2.0,   'col3' => 'II',    'col2' => 'B'],
            ['col1' => 3.0,   'col3' => 'III',   'col2' => 'C '],
            ['col1' => 4.0,   'col3' => 'IV',    'col2' => 'D'],
            ['col1' => 5.0,   'col3' => 'V',     'col2' => 0],
            ['col1' => 4.545454545454545,  'col3' => 0,       'col2' => 'F'],
            ['col1' => 7.0,   'col3' => 'VII',   'col2' => 'G'],
            ['col1' => 8.0,   'col3' => 'VIII',  'col2' => 'H'],
            ['col1' => 9.0,   'col3' => 'IX',    'col2' => 'I'],
            ['col1' => 10.0,  'col3' => 'X',     'col2' => 'J'],
            ['col1' => 1.0,   'col3' => 'I',     'col2' => 'A'],
        ];

        $expectedOutputs = [
            ['col3' => 'I'],
            ['col3' => 'II'],
            ['col3' => 'III'],
            ['col3' => 'IV'],
            ['col3' => 'V'],
            ['col3' => 0],
            ['col3' => 'VII'],
            ['col3' => 'VIII'],
            ['col3' => 'IX'],
            ['col3' => 'X'],
            ['col3' => 'I'],
        ];

        $this->assertEquals($expectedInputs, $this->dataSet->getInputsMatrix());
        $this->assertEquals($expectedOutputs, $this->dataSet->getOutputsMatrix());

        $this->assertEquals(count($expectedInputs), $this->dataSet->getSize());
        $this->assertEquals(count($this->dataSet->getInputsMatrix()), count($this->dataSet->getOutputsMatrix()));
        $this->assertEquals(4.9586776859504136, $this->dataSet->getInputAvg('col1'));
        $this->assertEquals(0, $this->dataSet->getInputAvg('col2'));
        $this->assertEquals(0, $this->dataSet->getInputAvg('col3'));
        $this->assertEquals(0, $this->dataSet->getOutputAvg('col3'));
        $this->assertEquals(['col1' => 4.9586776859504136, 'col3' => 0, 'col2' => 0], $this->dataSet->getInputsAvg());
        $this->assertEquals(['col3' => 0], $this->dataSet->getOutputsAvg());

        foreach ($this->dataSet as $index => $instance) {
            $this->assertInstanceOf(Instance::class, $instance);
            $this->assertEquals($expectedInputs[$index], $instance->getInputs());
            $this->assertEquals($expectedOutputs[$index], $instance->getOutputs());

        }
    }

    /**
     * @test
     */
    public function applying_replaceWithMOstCommon_policy_replace_empty_values_with_most_common_one()
    {
        //preserving keys
        $mapper = new Mapper(
            [
                'col1' => CleanPolicy::replaceWithMostCommon(),
                'col3' => CleanPolicy::replaceWithMostCommon(),
                'col2' => CleanPolicy::replaceWithMostCommon(),
            ],
            [
                'col3' => CleanPolicy::replaceWithMostCommon(),
            ]
        );

        $this->dataSet->prepare($mapper);

        $expectedInputs = [
            ['col1' => 1.0,   'col3' => 'I',     'col2' => 'A'],
            ['col1' => 2.0,   'col3' => 'II',    'col2' => 'B'],
            ['col1' => 3.0,   'col3' => 'III',   'col2' => 'C '],
            ['col1' => 4.0,   'col3' => 'IV',    'col2' => 'D'],
            ['col1' => 5.0,   'col3' => 'V',     'col2' => 'A'],
            ['col1' => 1,     'col3' => 'I',     'col2' => 'F'],
            ['col1' => 7.0,   'col3' => 'VII',   'col2' => 'G'],
            ['col1' => 8.0,   'col3' => 'VIII',  'col2' => 'H'],
            ['col1' => 9.0,   'col3' => 'IX',    'col2' => 'I'],
            ['col1' => 10.0,  'col3' => 'X',     'col2' => 'J'],
            ['col1' => 1.0,   'col3' => 'I',     'col2' => 'A'],
        ];

        $expectedOutputs = [
            ['col3' => 'I'],
            ['col3' => 'II'],
            ['col3' => 'III'],
            ['col3' => 'IV'],
            ['col3' => 'V'],
            ['col3' => 'I'],
            ['col3' => 'VII'],
            ['col3' => 'VIII'],
            ['col3' => 'IX'],
            ['col3' => 'X'],
            ['col3' => 'I'],
        ];

        $this->assertEquals($expectedInputs, $this->dataSet->getInputsMatrix());
        $this->assertEquals($expectedOutputs, $this->dataSet->getOutputsMatrix());

        $this->assertEquals(count($expectedInputs), $this->dataSet->getSize());
        $this->assertEquals(count($this->dataSet->getInputsMatrix()), count($this->dataSet->getOutputsMatrix()));
        $this->assertEquals(4.6363636363636367, $this->dataSet->getInputAvg('col1'));
        $this->assertEquals(0, $this->dataSet->getInputAvg('col2'));
        $this->assertEquals(0, $this->dataSet->getInputAvg('col3'));
        $this->assertEquals(0, $this->dataSet->getOutputAvg('col3'));
        $this->assertEquals(['col1' => 4.6363636363636367, 'col3' => 0, 'col2' => 0], $this->dataSet->getInputsAvg());
        $this->assertEquals(['col3' => 0], $this->dataSet->getOutputsAvg());

        foreach ($this->dataSet as $index => $instance) {
            $this->assertInstanceOf(Instance::class, $instance);
            $this->assertEquals($expectedInputs[$index], $instance->getInputs());
            $this->assertEquals($expectedOutputs[$index], $instance->getOutputs());

        }
    }

    /**
     * @test
     */
    public function method_prepare_sets_a_proper_array_of_instances()
    {
        $this->assertFalse($this->dataSet->isPrepared());

        $mapper = new Mapper(['col1', 'col2'], ['col3']);
        $this->dataSet->prepare($mapper);

        $this->assertTrue($this->dataSet->isPrepared());

        $this->assertInstanceOf(Mapper::class, $this->dataSet->getMapper());

        $this->assertInternalType('array', $this->dataSet->getInstances());
        $this->assertEquals($this->csvSize, count($this->dataSet->getInstances()));
        $this->assertEquals($this->csvSize, count($this->dataSet->getInputsMatrix()));
        $this->assertEquals($this->csvSize, count($this->dataSet->getOutputsMatrix()));

        foreach ($this->dataSet->getInstances() as $instance) {
            // any instance should contain two inputs
            $this->assertEquals(2, count($instance->getInputs()));
            // any instance should contain one output
            $this->assertEquals(1, count($instance->getOutputs()));
        }

        $expectedInputs = [
            ['col1' => 1, 'col2' => 'A'],
            ['col1' => 2, 'col2' => 'B'],
            ['col1' => 3, 'col2' => 'C '],
            ['col1' => 4, 'col2' => 'D'],
            ['col1' => 5, 'col2' => ''],
            ['col1' => '', 'col2' => 'F'],
            ['col1' => 7, 'col2' => 'G'],
            ['col1' => 8, 'col2' => 'H'],
            ['col1' => 9, 'col2' => 'I'],
            ['col1' => 10, 'col2' => 'J'],
            ['col1' => 1, 'col2' => 'A'],
        ];

        $expectedOutputs = [
            ['col3' => 'I'],
            ['col3' => 'II'],
            ['col3' => 'III'],
            ['col3' => 'IV'],
            ['col3' => 'V'],
            ['col3' => ''],
            ['col3' => 'VII'],
            ['col3' => 'VIII'],
            ['col3' => 'IX'],
            ['col3' => 'X'],
            ['col3' => 'I'],
        ];

        $this->assertEquals($expectedInputs, $this->dataSet->getInputsMatrix());
        $this->assertEquals($expectedOutputs, $this->dataSet->getOutputsMatrix());

        $this->assertEquals(4.545454545454545, $this->dataSet->getInputAvg('col1'));
        $this->assertEquals(0, $this->dataSet->getInputAvg('col2'));
        $this->assertEquals(0, $this->dataSet->getOutputAvg('col3'));
        $this->assertEquals(['col1' => 4.545454545454545, 'col2' => 0], $this->dataSet->getInputsAvg());
        $this->assertEquals(['col3' => 0], $this->dataSet->getOutputsAvg());

        foreach ($this->dataSet as $index => $instance) {
            $this->assertInstanceOf(Instance::class, $instance);
            $this->assertEquals($expectedInputs[$index], $instance->getInputs());
            $this->assertEquals($expectedOutputs[$index], $instance->getOutputs());
        }
    }

    /**
     * @test
     */
    public function method_getRawData_returns_raw_data_array()
    {
        $this->assertInternalType('array', $this->dataSet->getRawData());
        $this->assertCount($this->csvSize, $this->dataSet->getRawData());
        $this->assertEquals(
            [
                ['col1' => 1 , 'col2' =>  'A', 'col3' => 'I'],
                ['col1' => 2 , 'col2' => 'B',  'col3' =>'II'],
                ['col1' => 3 , 'col2' => 'C ', 'col3' => 'III'],
                ['col1' => 4 , 'col2' => 'D',  'col3' => 'IV'],
                ['col1' => 5 , 'col2' => '',   'col3' =>'V'],
                ['col1' => '', 'col2' => 'F',  'col3' =>''],
                ['col1' => 7 , 'col2' => 'G',  'col3' =>'VII'],
                ['col1' => 8 , 'col2' => 'H',  'col3' =>'VIII'],
                ['col1' => 9 , 'col2' => 'I',  'col3' =>'IX'],
                ['col1' => 10, 'col2' => 'J',  'col3' =>'X'],
                ['col1' => 1 , 'col2' => 'A',  'col3' =>'I'],
            ],
            $this->dataSet->getRawData()
        );

    }

    /**
     * @test
     */
    public function should_be_able_to_rename_indexes()
    {
        //Preserving keys
        $mapper = new Mapper(['col3', 'col2'], ['col3']);
        $this->dataSet->prepare($mapper);
        $this->dataSet->rename(['col1' => 'Arab numbers', 'col2' => 'Latin alphabet', 'col3' => 'Roman numbers']);

        $expectedInputs = [
            ['Roman numbers' => 'I',    'Latin alphabet' => 'A'],
            ['Roman numbers' => 'II',   'Latin alphabet' => 'B'],
            ['Roman numbers' => 'III',  'Latin alphabet' => 'C '],
            ['Roman numbers' => 'IV',   'Latin alphabet' => 'D'],
            ['Roman numbers' => 'V',    'Latin alphabet' => ''],
            ['Roman numbers' => '',     'Latin alphabet' => 'F'],
            ['Roman numbers' => 'VII',  'Latin alphabet' => 'G'],
            ['Roman numbers' => 'VIII', 'Latin alphabet' => 'H'],
            ['Roman numbers' => 'IX',   'Latin alphabet' => 'I'],
            ['Roman numbers' => 'X',    'Latin alphabet' => 'J'],
            ['Roman numbers' => 'I',    'Latin alphabet' => 'A'],
        ];

        $expectedOutputs = [
            ['Roman numbers' => 'I'],
            ['Roman numbers' => 'II'],
            ['Roman numbers' => 'III'],
            ['Roman numbers' => 'IV'],
            ['Roman numbers' => 'V'],
            ['Roman numbers' => ''],
            ['Roman numbers' => 'VII'],
            ['Roman numbers' => 'VIII'],
            ['Roman numbers' => 'IX'],
            ['Roman numbers' => 'X'],
            ['Roman numbers' => 'I'],
        ];

        $this->assertEquals($expectedInputs, $this->dataSet->getInputsMatrix());
        $this->assertEquals($expectedOutputs, $this->dataSet->getOutputsMatrix());

        foreach ($this->dataSet as $index => $instance) {
            $this->assertInstanceOf(Instance::class, $instance);
            $this->assertEquals($expectedInputs[$index], $instance->getInputs());
            $this->assertEquals($expectedOutputs[$index], $instance->getOutputs());

        }

    }

    /**
     * @test
     * @expectedException Zeeml\DataSet\Exception\DataSetPreparationException
     */
    public function method_size__without_prepare_throw_exception()
    {
        $this->dataSet->getSize();
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
        $this->dataSet->prepare(new Mapper(['col1', 'col2'], ['col3']));
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
        $this->dataSet->prepare(new Mapper(['col1', 'col2'], ['col3']));
        $this->assertInternalType('array', $this->dataSet->getInstances());
        $this->assertCount($this->csvSize, $this->dataSet->getInstances());
    }

    /**
     * @test
     * @expectedException Zeeml\DataSet\Exception\DataSetPreparationException
     */
    public function direct_call_to_getInputsMatrix_fails()
    {
        $this->dataSet->getInputsMatrix();
    }

    /**
     * @test
     */
    public function call_getInputsMatrix_after_preparation_succeeds()
    {
        $this->dataSet->prepare(new Mapper(['col1', 'col2'], ['col3']));
        $this->assertInternalType('array', $this->dataSet->getInputsMatrix());
        $this->assertCount($this->csvSize, $this->dataSet->getInputsMatrix());
    }

    /**
     * @test
     * @expectedException Zeeml\DataSet\Exception\DataSetPreparationException
     */
    public function direct_call_to_getOutputMatrix_fails()
    {
        $this->dataSet->getInputsMatrix();
    }

    /**
     * @test
     * @expectedException Zeeml\DataSet\Exception\DataSetPreparationException
     */
    public function preparing_twice_fails()
    {
        $this->dataSet->prepare(new Mapper(['col1', 'col2'], ['col3']));
        $this->dataSet->prepare(new Mapper(['col1', 'col2'], ['col3']));
    }

    /**
     * @test
     * @expectedException Zeeml\DataSet\Exception\DataSetPreparationException
     */
    public function preparing_non_existing_inputs_keys_fails()
    {
        $this->dataSet->prepare(new Mapper(['abc', 'col2'], ['col3']));
    }

    /**
     * @test
     * @expectedException Zeeml\DataSet\Exception\DataSetPreparationException
     */
    public function preparing_non_existing_outputs_keys_fails()
    {
        $this->dataSet->prepare(new Mapper(['col1', 'col2'], ['abc']));
    }


    /**
     * @test
     */
    public function call_getOutputMatrix_after_preparation_succeeds()
    {
        $this->dataSet->prepare(new Mapper(['col1', 'col2'], ['col3']));
        $this->assertInternalType('array', $this->dataSet->getOutputsMatrix());
        $this->assertCount($this->csvSize, $this->dataSet->getOutputsMatrix());
    }
}
