<?php

use Zeeml\DataSet\DataSetFactory;
use PHPUnit\Framework\TestCase;
use Zeeml\DataSet\Processor\AbstractProcessor;
use Zeeml\DataSet\DataSet\Instance;
use Zeeml\DataSet\DataSet\Mapper;
use Zeeml\DataSet\Processor\CsvProcessor;
use Zeeml\Algorithms\Prediction\Linear\SimpleLinearRegression;

/**
 * DataSet test case.
 */
class DataSetTest extends TestCase
{
    /**
     *
     * @var \Zeeml\DataSet\DataSet
     */
    private $dataset;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->dataset = DataSetFactory::create( __DIR__ . '/fixtures/data.csv');
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->dataset = null;
        parent::tearDown();
    }

    /**
     * @test
     */
    public function method_size_returns_an_integer()
    {
        $this->assertEquals(10, $this->dataset->size());
    }

    /**
     * @test
     */
    public function method_get_returns_a_data_array()
    {
        $this->assertInternalType('array', $this->dataset->get());
        $this->assertEquals(10, count($this->dataset->get()));
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
            $this->dataset->get()
        );
    }
    
    /**
     * @test
     */
    public function method_processor_returns_an_instance_of_a_processor()
    {
        $this->assertInstanceOf(AbstractProcessor::class, $this->dataset->processor());
    }

    /**
     * @test
     */
    public function processor_is_a_csv_processor()
    {
        $this->assertInstanceOf(CsvProcessor::class, $this->dataset->processor());
    }
    
    /**
     * @test
     * @expectedException Zeeml\DataSet\Exception\DataSetPreparationException
     */
    public function direct_call_to_function_instances_fails()
    {
        $this->dataset->instances();
    }
    
    /**
     * @test
     */
    public function method_prepare_sets_a_proper_array_of_instances()
    {
        $mapper = new Mapper([0,1], [2]);
        $this->dataset->prepare($mapper);
        $this->assertInternalType('array', $this->dataset->instances());
        $this->assertEquals(10, count($this->dataset->instances()));

        $this->assertInstanceOf(Instance::class, $this->dataset->instance(0));
        
        // any instance should contain two dimensions
        $this->assertEquals(2, count($this->dataset->instance(0)->dimensions()));
        
        // any instance should contain one output
        $this->assertEquals(1, count($this->dataset->instance(0)->outputs()));
        
        // there is no line 10
        $this->assertFalse($this->dataset->instance(10));

        $this->assertEquals(
            $this->dataset->rawDimensions(),
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
            $this->dataset->rawOutputs(),
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
     * @expectedException Zeeml\DataSet\Exception\DataSetPreparationException
     */
    public function method_prepare_fails_whith_bad_params()
    {
        $mapper = new Mapper([3], [1]); // no key 3 in fixture
        $this->dataset->prepare($mapper);
    }

    public function test_save_algorithms()
    {
        $this->assertTrue(is_array($this->dataset->algorithms()));
        $this->assertEmpty($this->dataset->algorithms('Test'));

        $this->dataset->addAlgorithm(new SimpleLinearRegression());

        $this->assertInstanceOf(SimpleLinearRegression::class, $this->dataset->algorithm(SimpleLinearRegression::class));

        $this->assertCount(1, $this->dataset->algorithms());

    }
}
