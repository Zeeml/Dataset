<?php

use Zeeml\Dataset\Dataset;
use PHPUnit\Framework\TestCase;
use Zeeml\Dataset\Processor\AbstractProcessor;
use Zeeml\Dataset\Dataset\Instance;
use Zeeml\Dataset\Dataset\Mapper;
use Zeeml\Dataset\Processor\CsvProcessor;

/**
 * Dataset test case.
 */
class DatasetTest extends TestCase
{
    /**
     *
     * @var Dataset
     */
    private $dataset;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->dataset = Dataset::factory(
            __DIR__ . '/fixtures/data.csv', 
            Dataset::PREDICTION
        );
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
     * @expectedException Zeeml\Dataset\Exception\DatasetPreparationException
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
        
    }
    
    /**
     * @test
     * @expectedException Zeeml\Dataset\Exception\DatasetPreparationException
     */
    public function method_prepare_fails_whith_bad_params()
    {
        $mapper = new Mapper([3], [1]); // no key 3 in fixture
        $this->dataset->prepare($mapper);
    }
}
