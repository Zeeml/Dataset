<?php

use Zeeml\Dataset\Dataset;
use PHPUnit\Framework\TestCase;
use Zeeml\Dataset\Processor\AbstractProcessor;
use Zeeml\Dataset\Dataset\Instance;

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
    public function method_prepare_sets_a_proper_array_of_instances()
    {
        $this->dataset->prepare(1,2);
        $this->assertInternalType('array', $this->dataset->instances());
        $this->assertEquals(10, count($this->dataset->instances()));
        
        
        $this->assertInstanceOf(Instance::class, $this->dataset->instance(0));
        $this->assertEquals(1, count($this->dataset->instance(0)->inputs()));
        $this->assertEquals(2, count($this->dataset->instance(0)->outputs()));
        
        $this->assertFalse($this->dataset->instance(10));
        
    }
    
    /**
     * @test
     * @expectedException Zeeml\Dataset\Exception\DatasetPreparationException
     */
    public function method_prepare_fails_whith_bad_params()
    {
        $this->dataset->prepare(3,1);
    }
}
